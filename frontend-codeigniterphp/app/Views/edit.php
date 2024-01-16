<?= $this->include('templates/header'); ?>

<div class="container mt-5">
    <a href="<?= base_url() ?>" class="btn btn-primary mb-5" name="Kembali">Kembali</a>
    <input type="hidden" class="id" value="<?= $article ? $article->id : null; ?>">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control title" id="title" name="title" value="<?= $article ? $article->title : null; ?>">
    </div>
    <div class="form-group">
        <label for="content">Content:</label>
        <textarea class="form-control content" id="content" name="content" ><?= $article ? $article->content : null; ?></textarea>
    </div>
    <div class="form-group">
        <label for="category">Category:</label>
        <input type="text" class="form-control category" id="category" name="category" value="<?= $article ? $article->category : null; ?>">
    </div>
    <button type="submit" class="btn btn-primary publish" name="publish">Publish</button>
    <button type="submit" class="btn btn-secondary draft" name="draft">Draft</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
$(document).ready(function() {
    $(".publish").click(function() {
        let data = new FormData()
        data.append("id", $(".id").val());
        data.append("title", $(".title").val());
        data.append("content", $(".content").val());
        data.append("category", $(".category").val());
        data.append("status", "Publish");
        $.ajax({
            url: "<?= base_url("update"); ?>",
            data: data,
            method: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    alert(response.message)
                    window.location.href = "<?= base_url(); ?>"
                } else {
                    alert(response.message)
                }
            },
            onError: function(response) {
                alert(response.message)
            }
        });
    })

    $(".draft").click(function() {
        let data = new FormData()
        data.append("id", $(".id").val());
        data.append("title", $(".title").val());
        data.append("content", $(".content").val());
        data.append("category", $(".category").val());
        data.append("status", "Draft");
        $.ajax({
            url: "<?= base_url("update"); ?>",
            data: data,
            method: "POST",
            dataType: "json",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    alert(response.message)
                    window.location.href = "<?= base_url(); ?>"
                } else {
                    alert(response.message)
                }
            },
            onError: function(response) {
                alert(response.message)
            }
        });
    })
})
</script>

<?= $this->include('templates/footer'); ?>