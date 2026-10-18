<?php
/**
 * Nguồn dữ liệu bài viết.
 *
 * Hiện tại dữ liệu nằm trong mảng PHP bên dưới. Khi có database, chỉ cần
 * viết lại 2 hàm getArticles() và getArticleById() để truy vấn bảng
 * `articles` (id, title, category, excerpt, content, img, author, created_at)
 * -- phần giao diện không cần sửa.
 *
 * `content` là mảng các đoạn văn (mỗi phần tử là 1 đoạn).
 */

function getArticles(): array
{
    return [
        [
            'id' => 1,
            'title' => 'Cách chọn laptop cho sinh viên: đừng chỉ nhìn vào giá',
            'category' => 'Tư vấn',
            'excerpt' => 'Sinh viên cần một chiếc laptop bền, pin tốt và đủ mạnh cho 4 năm học. Đây là các tiêu chí nên cân nhắc trước khi xuống tiền.',
            'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2023-09-05',
            'content' => [
                'Khi chọn laptop, nhiều bạn sinh viên thường chọn theo mức giá thấp nhất. Tuy nhiên, một chiếc máy phải đồng hành với bạn trong nhiều năm học, nên nên cân đối giữa hiệu năng, độ bền và tính di động.',
                'Trước tiên hãy xác định nhu cầu: học văn phòng, lập trình, thiết kế đồ hoạ hay chơi game. Nhu cầu quyết định cấu hình tối thiểu cần có, tránh trả tiền cho phần hiệu năng bạn không dùng tới.',
                'Về RAM, mức 16GB đang là lựa chọn an toàn cho đa số nhu cầu hiện nay, đặc biệt nếu bạn hay mở nhiều tab trình duyệt hoặc chạy máy ảo. Ổ cứng nên là SSD để máy khởi động và mở ứng dụng nhanh.',
                'Pin và trọng lượng cũng quan trọng không kém. Nếu bạn phải mang máy đến trường mỗi ngày, hãy ưu tiên máy mỏng nhẹ và pin dùng được cả buổi. Cuối cùng, đừng quên kiểm tra chính sách bảo hành và hậu mãi của nơi bán.',
            ],
        ],
        [
            'id' => 2,
            'title' => 'MacBook hay laptop Windows: nên chọn bên nào?',
            'category' => 'So sánh',
            'excerpt' => 'Hai hệ sinh thái, hai phong cách sử dụng. Bài viết giúp bạn xác định hệ điều hành nào phù hợp với công việc của mình.',
            'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2023-09-12',
            'content' => [
                'MacBook nổi bật ở thời lượng pin, độ hoàn thiện phần cứng và sự đồng bộ với các thiết bị Apple khác. Đây là lựa chọn quen thuộc của người làm sáng tạo nội dung và lập trình viên phát triển ứng dụng iOS.',
                'Laptop Windows lại có thế mạnh ở sự đa dạng: nhiều phân khúc giá, nhiều hãng, và khả năng nâng cấp linh hoạt hơn ở một số dòng máy. Kho phần mềm và game trên Windows cũng rộng hơn.',
                'Nếu công việc của bạn phụ thuộc vào một phần mềm chỉ chạy trên một hệ điều hành, hãy chọn theo phần mềm đó trước. Nếu không, bạn nên trải nghiệm trực tiếp cả hai để chọn hệ điều hành mình thấy thoải mái nhất.',
            ],
        ],
        [
            'id' => 3,
            'title' => 'Laptop gaming: tản nhiệt quan trọng hơn bạn nghĩ',
            'category' => 'Gaming',
            'excerpt' => 'Chip mạnh mà tản nhiệt kém thì hiệu năng cũng bị giảm. Cùng tìm hiểu vì sao hệ thống làm mát lại quan trọng với máy chơi game.',
            'img' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2023-09-20',
            'content' => [
                'Khi chơi game hoặc render nặng, CPU và GPU toả rất nhiều nhiệt. Nếu hệ thống tản nhiệt không theo kịp, máy sẽ tự giảm xung nhịp để bảo vệ linh kiện, khiến hiệu năng thực tế thấp hơn thông số trên giấy.',
                'Vì vậy, khi so sánh các mẫu laptop gaming, bạn nên xem thêm các bài đánh giá về nhiệt độ và độ ồn khi chạy tải nặng, thay vì chỉ so sánh tên chip và card đồ hoạ.',
                'Trong quá trình sử dụng, hãy đặt máy ở nơi thoáng, không chặn các khe hút gió, và vệ sinh quạt định kỳ. Một đế tản nhiệt cũng có thể giúp nhiệt độ ổn định hơn trong các phiên chơi dài.',
            ],
        ],
        [
            'id' => 4,
            'title' => 'Mẹo kéo dài tuổi thọ pin laptop',
            'category' => 'Mẹo hay',
            'excerpt' => 'Một vài thói quen nhỏ khi sạc và sử dụng có thể giúp pin laptop của bạn bền hơn trong nhiều năm.',
            'img' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2023-10-02',
            'content' => [
                'Pin lithium trên laptop sẽ chai dần theo số chu kỳ sạc và nhiệt độ môi trường. Bạn không thể ngăn pin chai hoàn toàn, nhưng có thể làm chậm quá trình này.',
                'Hạn chế để máy nóng khi sạc, tránh đặt laptop trên chăn, nệm. Nhiều dòng máy có chế độ giới hạn mức sạc tối đa, hãy bật chế độ này nếu bạn thường xuyên cắm sạc liên tục.',
                'Ngoài ra, giảm độ sáng màn hình, tắt các ứng dụng chạy nền không cần thiết và dùng bộ sạc đúng chuẩn của hãng sẽ giúp pin hoạt động ổn định hơn.',
            ],
        ],
        [
            'id' => 5,
            'title' => 'Laptop doanh nhân: những tiêu chí vượt ra ngoài cấu hình',
            'category' => 'Tư vấn',
            'excerpt' => 'Với người đi công tác nhiều, bàn phím, độ bền và bảo mật đôi khi quan trọng hơn con số trên bảng cấu hình.',
            'img' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2023-10-10',
            'content' => [
                'Người dùng doanh nhân thường làm việc nhiều giờ liền và di chuyển liên tục, nên trải nghiệm gõ phím, trọng lượng và độ bền của máy được đặt lên hàng đầu.',
                'Các tính năng bảo mật như cảm biến vân tay, camera hồng ngoại nhận diện khuôn mặt và chip bảo mật phần cứng giúp bảo vệ dữ liệu công việc khi bạn làm việc ở nơi công cộng.',
                'Cuối cùng, hãy cân nhắc các cổng kết nối. Một chiếc máy mỏng nhẹ nhưng thiếu cổng cần thiết có thể buộc bạn phải mang theo nhiều phụ kiện đi kèm.',
            ],
        ],
        [
            'id' => 6,
            'title' => 'SSD NVMe và SATA: khác nhau thế nào, nên chọn loại nào?',
            'category' => 'Linh kiện',
            'excerpt' => 'Cùng là SSD nhưng NVMe và SATA có tốc độ, cách kết nối và mức giá khác nhau. Hiểu đúng để không chọn nhầm khi mua laptop hay nâng cấp ổ cứng.',
            'img' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ban biên tập TechNova',
            'created_at' => '2026-10-18',
            'content' => [
                'SSD SATA và SSD NVMe đều lưu dữ liệu bằng bộ nhớ flash, nên cả hai đều nhanh hơn ổ cứng cơ truyền thống rất nhiều. Điểm khác nhau nằm ở cách ổ kết nối và trao đổi dữ liệu với máy tính.',
                'SSD SATA dùng cổng SATA, phổ biến nhất hiện nay là chuẩn SATA III với băng thông lý thuyết khoảng 6 Gbps. Loại này thường có dạng ổ 2.5 inch, nhưng cũng có phiên bản nhỏ gọn theo chuẩn M.2. Tốc độ thực tế của SSD SATA thường chỉ ở mức vài trăm MB/s.',
                'SSD NVMe dùng giao thức NVMe, được thiết kế riêng cho bộ nhớ flash và kết nối trực tiếp qua đường truyền PCIe. Nhờ đường truyền rộng hơn, tốc độ có thể cao gấp nhiều lần SATA: ổ dùng PCIe 3.0 thường đạt vài nghìn MB/s, còn ổ PCIe 4.0 trở lên còn nhanh hơn nữa.',
                'Có một điểm rất dễ nhầm khi mua: M.2 chỉ là chuẩn về hình dạng và kích thước, còn NVMe là giao thức truyền dữ liệu. Vì vậy tồn tại cả SSD M.2 dùng SATA lẫn SSD M.2 dùng NVMe, nhìn bên ngoài khá giống nhau nhưng tốc độ khác hẳn. Hãy kiểm tra cả loại ổ lẫn loại giao thức mà khe M.2 trên máy của bạn hỗ trợ trước khi mua.',
                'Vậy nên chọn loại nào? Nếu bạn chủ yếu học tập, làm văn phòng, lướt web và xem phim, SSD SATA đã đủ nhanh và thường có giá dễ chịu hơn. Nếu bạn lập trình, thiết kế đồ hoạ, dựng video, chơi game hoặc thường xuyên làm việc với tập tin dung lượng lớn, SSD NVMe sẽ giúp sao chép, mở dự án và tải dữ liệu nhanh hơn rõ rệt.',
                'Cũng cần lưu ý rằng ổ NVMe tốc độ cao thường toả nhiệt nhiều hơn, nên máy có khả năng tản nhiệt tốt sẽ phát huy được hiệu năng ổn định hơn. Ngoài ra, nhiều laptop đời mới được thiết kế chỉ với khe M.2, nên SSD NVMe gần như là lựa chọn mặc định ở các dòng máy mỏng nhẹ.',
                'Khi xem cấu hình laptop tại TechNova, bạn hãy để ý dòng thông tin về ổ cứng: loại SSD, giao thức và dung lượng đều ảnh hưởng đến trải nghiệm sử dụng hằng ngày. Nội dung bài viết có tham khảo bài so sánh SSD NVMe và SATA trên Memoryzone.',
            ],
        ],
    ];
}

function getArticleById(int $id): ?array
{
    foreach (getArticles() as $article) {
        if ($article['id'] === $id) {
            return $article;
        }
    }
    return null;
}
