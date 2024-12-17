<div class="card">
    <img src="../uploads/articles/<?= htmlspecialchars($article['image']); ?>" alt="<?= htmlspecialchars($article['title']); ?>">
    <h3><?= htmlspecialchars($article['title']); ?></h3>
    <p><?= substr(htmlspecialchars($article['content']), 0, 100); ?>...</p>
    <a href="view_article.php?id=<?= $article['id']; ?>">Read More</a>
</div>
