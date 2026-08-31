<?php if (($totalPages ?? 1) > 1): ?>
<nav class="pagination">
    <?php if ($page > 1): ?>
        <a class="pagination-link" href="index.php?action=<?= htmlspecialchars($paginationAction) ?>&page=<?= $page - 1 ?>">&larr; Previous</a>
    <?php endif; ?>
    <span class="pagination-info"><?= $page ?> / <?= $totalPages ?></span>
    <?php if ($page < $totalPages): ?>
        <a class="pagination-link" href="index.php?action=<?= htmlspecialchars($paginationAction) ?>&page=<?= $page + 1 ?>">Next &rarr;</a>
    <?php endif; ?>
</nav>
<?php endif; ?>
