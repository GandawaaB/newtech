<!-- Blog Post -->
<div class="card mb-4">
    <div class="card-body">
        <p>
            <a href="{{ $post->category->path() }}">
                <span class="badge badge-pill badge-primary">{{ $post->category->name }}</span>
            </a>
        </p>
        <h2 class="card-title">
            <a href="{{ $post->path() }}">
                {{ $post->title }}
            </a>
        </h2>
        <img src ="{{asset('images/'. $post->image)}}" class="img-fluid" />
        <p class="card-text">{{ substr($post->body, 0, 200) }}</p>

        <a href="{{ $post->path() }}" class="btn btn-primary">
            Дэлгэрэнгүй &rarr;
        </a>
    </div>
    <div class="card-footer text-muted">
        Нийтэлсэн {{ $post->created_at->toDayDateTimeString() }}</a>
        <span class="badge badge-pill badge-info float-right">Сэтгэгдэл {{ $post->comments_count }}</span>
    </div>
</div>
