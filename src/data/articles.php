<?php
/**
 * Nguồn dữ liệu bài viết: đọc từ bảng `articles` trong database
 * (id, title, category, excerpt, content, img, author, created_at).
 * Bài viết được thêm / sửa / xoá trong trang admin, mục "Bài viết".
 *
 * Trong DB, `content` lưu mỗi đoạn văn trên 1 dòng; khi trả ra cho giao diện
 * thì `content` là mảng các đoạn văn (mỗi phần tử là 1 đoạn).
 */

require_once __DIR__ . '/../config/db.php';

function mapArticleRow(array $row): array
{
    $row['id'] = (int)$row['id'];
    $row['content'] = array_values(array_filter(preg_split('/\R/u',$row['content']), fn($p) => trim($p) !== ''));
    return $row;
}

function getArticles(): array
{
    global $pdo;
    $rows = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
    return array_map('mapArticleRow', $rows);
}

function getArticleById(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? mapArticleRow($row) : null;
}
