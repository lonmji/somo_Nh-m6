'use client';

import React, { useEffect, useState, useRef } from 'react';

/**
 * Component hiển thị bảng DataTable và flash message – dành cho Next.js (App Router)
 * @param {string} flashMessage - Nội dung thông báo
 * @param {Array}  tableData    - Mảng dữ liệu (mỗi object là một hàng)
 * @param {Array}  columns      - Mảng cấu hình cột [{ data: 'field', title: 'Label' }]
 * @param {number} pageLength   - Số dòng mỗi trang (mặc định 10)
 */
const DataTableWithFlash = ({
  flashMessage = 'Cập nhật dữ liệu thành công!',
  tableData = [],
  columns = [],
  pageLength = 10,
}) => {
  const [showFlash, setShowFlash] = useState(true);
  const [isClient, setIsClient] = useState(false);
  const tableRef = useRef(null);

  // Đảm bảo component chỉ chạy ở client (tránh lỗi window undefined)
  useEffect(() => {
    setIsClient(true);
  }, []);

  // Khởi tạo DataTables khi component mount và mỗi khi dữ liệu thay đổi
  useEffect(() => {
    if (!isClient || !tableData.length || !columns.length) return;

    let isMounted = true;

    const initDataTable = async () => {
      try {
        // Import jQuery và DataTables động (chỉ chạy ở client)
        const $ = (await import('jquery')).default;
        await import('datatables.net');
        // DataTables tự động gắn vào $.fn.DataTable

        if (!isMounted) return;
        if (!tableRef.current) return;

        // Hủy instance cũ nếu có
        if ($.fn.DataTable.isDataTable(tableRef.current)) {
          $(tableRef.current).DataTable().destroy();
        }

        // Khởi tạo mới
        $(tableRef.current).DataTable({
          pageLength,
          data: tableData,
          columns,
          language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
          },
        });
      } catch (error) {
        console.error('Lỗi khi tải DataTables:', error);
      }
    };

    initDataTable();

    // Cleanup: hủy DataTable khi component unmount hoặc dữ liệu thay đổi
    return () => {
      isMounted = false;
      if (tableRef.current && typeof window !== 'undefined') {
        const $ = window.jQuery;
        if ($ && $.fn.DataTable && $.fn.DataTable.isDataTable(tableRef.current)) {
          $(tableRef.current).DataTable().destroy();
        }
      }
    };
  }, [isClient, tableData, columns, pageLength]);

  // Tự động ẩn flash message sau 3 giây
  useEffect(() => {
    if (!showFlash) return;
    const timer = setTimeout(() => setShowFlash(false), 3000);
    return () => clearTimeout(timer);
  }, [showFlash]);

  return (
    <div className="data-table-wrapper">
      {/* Flash message */}
      {showFlash && (
        <div
          id="flash-message"
          style={{
            padding: '12px 20px',
            marginBottom: '20px',
            backgroundColor: '#d4edda',
            color: '#155724',
            border: '1px solid #c3e6cb',
            borderRadius: '4px',
          }}
        >
          {flashMessage}
        </div>
      )}

      {/* Bảng DataTable */}
      <table className="data-table" ref={tableRef}>
        <thead>
          <tr>
            {columns.map((col) => (
              <th key={col.data}>{col.title}</th>
            ))}
          </tr>
        </thead>
        {/* DataTable sẽ tự động render tbody */}
      </table>
    </div>
  );
};

export default DataTableWithFlash;