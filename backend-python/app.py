from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from flask_migrate import Migrate
from datetime import datetime, timedelta

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql://root:@localhost/post_article'
db = SQLAlchemy(app)
migrate = Migrate(app, db)

class Post(db.Model):
    id = db.Column(db.Integer, primary_key=True, autoincrement=True)
    title = db.Column(db.String(200), nullable=False)
    content = db.Column(db.Text, nullable=False)
    category = db.Column(db.String(100), nullable=False)
    created_date = db.Column(db.TIMESTAMP, default=datetime.utcnow)
    updated_date = db.Column(db.TIMESTAMP, default=datetime.utcnow, onupdate=datetime.utcnow)
    status = db.Column(db.String(100), nullable=False)

# Fungsi untuk memeriksa apakah nilai 'status' valid
def is_valid_status(status):
    return status in ['Publish', 'Draft', 'Trash']

@app.route('/article', methods=['POST'])
def create_post():
    data = request.json

    # Validate title, content, category, and status
    if not ('title' in data and 'content' in data and 'category' in data and 'status' in data):
        return jsonify({'error': 'Title, content, category, and status are required'}), 400

    if len(data['title']) < 20:
        return jsonify({'error': 'Title must be at least 20 characters'}), 400

    if len(data['content']) < 200:
        return jsonify({'error': 'Content must be at least 200 characters'}), 400

    if len(data['category']) < 3:
        return jsonify({'error': 'Category must be at least 3 characters'}), 400

    if not is_valid_status(data['status']):
        return jsonify({'error': 'Invalid status value'}), 400

    # Update the updated_date field
    current_utc_time = datetime.utcnow()

    # Add 7 hours to the current UTC time
    new_utc_time = current_utc_time + timedelta(hours=7)

    new_post = Post(
        title=data['title'],
        content=data['content'],
        category=data['category'],
        status=data['status'],
        created_date=new_utc_time,
        updated_date=new_utc_time
    )
    db.session.add(new_post)
    db.session.commit()
    return jsonify({'message': 'Post created successfully'}), 201

@app.route('/article', methods=['GET'])
@app.route('/article/<int:limit>/<int:offset>', methods=['GET'])
def get_paginated_posts(limit=None, offset=None):
    if limit is not None and offset is not None:
        # Query for paginated posts with limit and offset
        posts = Post.query.offset(offset).limit(limit).all()
    else:
        # If limit and offset are not provided, fetch all posts
        posts = Post.query.all()

    result = []
    for post in posts:
        post_data = {
            'id': post.id,
            'title': post.title,
            'content': post.content,
            'category': post.category,
            'status': post.status
        }
        result.append(post_data)

    return jsonify({'posts': result})

@app.route('/article/<int:id>', methods=['GET'])
def get_post_by_id(id):
    post = Post.query.get(id)
    if not post:
        return jsonify({'error': 'Post not found'}), 404

    post_data = {
        'id': post.id,
        'title': post.title,
        'content': post.content,
        'category': post.category,
        'status': post.status
    }

    return jsonify({'post': post_data})

@app.route('/article/<int:id>', methods=['PUT', 'PATCH'])
def update_post_by_id(id):
    post = Post.query.get(id)
    if not post:
        return jsonify({'error': 'Post not found'}), 404

    data = request.json

    # Validate title, content, category, and status
    if 'title' in data and len(data['title']) < 20:
        return jsonify({'error': 'Title must be at least 20 characters'}), 400

    if 'content' in data and len(data['content']) < 200:
        return jsonify({'error': 'Content must be at least 200 characters'}), 400

    if 'category' in data and len(data['category']) < 3:
        return jsonify({'error': 'Category must be at least 3 characters'}), 400

    if 'status' in data and not is_valid_status(data['status']):
        return jsonify({'error': 'Invalid status value'}), 400

    if 'title' in data:
        post.title = data['title']
    if 'content' in data:
        post.content = data['content']
    if 'category' in data:
        post.category = data['category']
    if 'status' in data:
        post.status = data['status']

    # Update the updated_date field
    current_utc_time = datetime.utcnow()

    # Add 7 hours to the current UTC time
    new_utc_time = current_utc_time + timedelta(hours=7)

    post.updated_date = new_utc_time

    db.session.commit()

    return jsonify({'message': 'Post updated successfully'})

@app.route('/article/<int:id>', methods=['DELETE'])
def delete_post_by_id(id):
    post = Post.query.get(id)
    if not post:
        return jsonify({'error': 'Post not found'}), 404

    db.session.delete(post)
    db.session.commit()

    return jsonify({'message': 'Post deleted successfully'})

if __name__ == '__main__':
    app.run(debug=True)