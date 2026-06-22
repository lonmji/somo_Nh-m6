import React, { useEffect, useState, useRef } from 'react';
// Giả sử bạn đã cài đặt các thư viện:
import $ from 'jquery';
import 'datatables.net';    // DataTables core
import 'datatables.net-dt/css/jquery.dataTables.css'; // nếu dùng CSS mặc định

function App() {
  // State điều khiển hiển thị flash message
  const [showFlash, setShowFlash] = useState(true);

  // Ref để tham chiếu tới bảng nếu cần (có thể bỏ qua nếu dùng class selector)
  const tableRef = useRef(null);

  // 1. Khởi tạo DataTables khi component mount
  useEffect(() => {
    // Kiểm tra DataTables đã sẵn sàng
    if (typeof $.fn.DataTable !== 'undefined') {
      $('.data-table').DataTable({
        pageLength: 10,
        language: {
          search: 'Search:',
          lengthMenu: 'Show _MENU_ entries'
        }
      });
    }

    // Cleanup: hủy DataTables khi component unmount để tránh rò rỉ bộ nhớ
    return () => {
      if ($.fn.DataTable) {
        $('.data-table').DataTable().destroy();
      }
    };
  }, []); // Chạy một lần duy nhất khi mount

  // 2. Tự động ẩn flash message sau 3 giây
  useEffect(() => {
    if (!showFlash) return; // nếu đã ẩn thì không cần

    const timer = setTimeout(() => {
      setShowFlash(false);
    }, 3000);

    return () => clearTimeout(timer); // Xóa timer khi component unmount hoặc showFlash thay đổi
  }, [showFlash]);

  return (
    <div>
      {/* Flash message – render có điều kiện */}
      {showFlash && (
        <div id="flash-message" style={{ padding: '10px', background: '#f0f0f0', border: '1px solid #ccc' }}>
          Đây là thông báo flash (tự động ẩn sau 3s)
        </div>
      )}

      {/* Bảng với class .data-table */}
      <table className="data-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Age</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John</td>
            <td>25</td>
          </tr>
          <tr>
            <td>Jane</td>
            <td>30</td>
          </tr>
        </tbody>
      </table>
    </div>
  );
}

export default App;