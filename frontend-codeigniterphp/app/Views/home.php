<?= $this->include('templates/header'); ?>

<div class="container mt-5">
    <a href="<?= base_url() ?>add" class="btn btn-primary mb-5" name="Add New">Add New</a>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="tab1" data-toggle="tab" href="#content1">Published</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab2" data-toggle="tab" href="#content2">Draft</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab3" data-toggle="tab" href="#content3">Trashed</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content mt-2">
        <div class="tab-pane fade show active" id="content1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($articles as $article): 
                    if($article->status === 'Publish'){
                ?>
                        <tr>
                            <td><?= $article->title ?></td>
                            <td><?= $article->category ?></td>
                            <td>
                                <a href="#" class="btn btn-primary edit" name="edit" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-pencil-alt"></i> 
                                </a>
                                <button class="btn btn-danger delete" name="delete" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>
                            </td>
                        </tr>
                    <?php 
                    }
                endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="content2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>
                <?php foreach ($articles as $article): 
                    if($article->status === 'Draft'){
                ?>
                        <tr>
                            <td><?= $article->title ?></td>
                            <td><?= $article->category ?></td>
                            <td>
                                <a href="#" class="btn btn-primary edit" name="edit" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-pencil-alt"></i> 
                                </a>
                                <button class="btn btn-danger delete" name="delete" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>
                            </td>
                        </tr>
                    <?php 
                    }
                endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="content3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($articles as $article): 
                    if($article->status === 'Trash'){
                ?>
                        <tr>
                            <td><?= $article->title ?></td>
                            <td><?= $article->category ?></td>
                            <td>
                                <a href="#" class="btn btn-primary edit" name="edit" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-pencil-alt"></i> 
                                </a>
                                <button class="btn btn-danger delete" name="delete" data-id="<?= $article->id; ?>">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>
                            </td>
                        </tr>
                    <?php 
                    }
                endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
$(document).ready(function() {
    $(document).on('click', '.edit', function() {
        let id = $(this).data('id')
        window.location.href = "<?= base_url() ?>id/" + id;
    })

    $(document).on('click', '.delete', function() {
        let id = $(this).data('id')
        $.ajax({
            url: "<?= base_url("trash"); ?>",
            data: {
                id: id
            },
            method: "POST",
            dataType: "json",
            success: function(response) {
                if(response.status)
                {
                    alert(response.message)
                    window.location.href = "<?= base_url(); ?>"
                }
                else
                {
                    alert(response.message)
                }
            },
            error: function(response) {
                alert(response.message)
            }
        });
    })
});
</script>

<?= $this->include('templates/footer'); ?>