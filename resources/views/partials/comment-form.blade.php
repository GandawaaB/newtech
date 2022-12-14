<div class="card my-4">
    <h5 class="card-header">Сэтгэгдэл бичих:</h5>
    <div class="card-body">
        <form action="{{ route('post.comment', $post) }}" method="POST">
            @csrf

            <div class="form-group">
                <textarea class="form-control" name="name" rows="1" placeholder="Нэр"  ></textarea><br>

                <textarea class="form-control" name="body" rows="3"  placeholder="Сэтгэгдэл" ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Илгээх</button>
        </form>
    </div>
</div>