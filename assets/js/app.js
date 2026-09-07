/**
 * PharmaCare - Main Application Script
 * Complete UI polish: Responsive drawer, dynamic table sorting, toast engine,
 * form validation loading spinners, Chart.js integrations, and search filters.
 */

// Global Toast System
window.showToast = function (message, type = 'success', title = '') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast-item toast-${type}`;

  let iconClass = 'fa-circle-check';
  let defaultTitle = 'Success';
  if (type === 'error') {
    iconClass = 'fa-circle-exclamation';
    defaultTitle = 'Error';
  } else if (type === 'warning') {
    iconClass = 'fa-triangle-exclamation';
    defaultTitle = 'Warning';
  } else if (type === 'info') {
    iconClass = 'fa-circle-info';
    defaultTitle = 'Information';
  }

  const displayTitle = title || defaultTitle;

  toast.innerHTML = `
    <div class="toast-icon">
      <i class="fa-solid ${iconClass}"></i>
    </div>
    <div class="toast-content">
      <div class="toast-title">${displayTitle}</div>
      <p class="toast-message">${message}</p>
    </div>
    <button type="button" class="toast-close" aria-label="Close">
      <i class="fa-solid fa-xmark"></i>
    </button>
  `;

  container.appendChild(toast);

  function dismissToast() {
    toast.classList.add('toast-hiding');
    toast.addEventListener('animationend', () => {
      toast.remove();
    });
  }

  const closeBtn = toast.querySelector('.toast-close');
  if (closeBtn) {
    closeBtn.addEventListener('click', dismissToast);
  }

  // Auto-dismiss after 4.5 seconds
  setTimeout(dismissToast, 4500);
};

document.addEventListener('DOMContentLoaded', function () {
  // 1. Sidebar & Mobile Drawer Navigation
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebar-toggle');
  const sidebarClose = document.getElementById('sidebar-close');
  const sidebarBackdrop = document.getElementById('sidebar-backdrop');

  function openSidebar() {
    if (sidebar) sidebar.classList.add('mobile-open');
    if (sidebarBackdrop) {
      sidebarBackdrop.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('mobile-open');
    if (sidebarBackdrop) {
      sidebarBackdrop.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function (e) {
      e.preventDefault();
      if (sidebar.classList.contains('mobile-open')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });
  }

  if (sidebarClose) {
    sidebarClose.addEventListener('click', function (e) {
      e.preventDefault();
      closeSidebar();
    });
  }

  if (sidebarBackdrop) {
    sidebarBackdrop.addEventListener('click', function () {
      closeSidebar();
    });
  }

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 992) {
      closeSidebar();
    }
  });

  // 2. Bootstrap Tooltips & Popovers
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }

  // 3. Global Search Shortcut (Cmd/Ctrl + K)
  const globalSearchInput = document.getElementById('global-search-input');
  document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (globalSearchInput) {
        globalSearchInput.focus();
      }
    }
  });

  // 4. Form Validation & Submit Loading Spinners
  const forms = document.querySelectorAll('.needs-validation, form');
  forms.forEach(form => {
    form.addEventListener('submit', function (e) {
      if (form.classList.contains('needs-validation')) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
          form.classList.add('was-validated');
          const firstInvalid = form.querySelector(':invalid');
          if (firstInvalid) firstInvalid.focus();
          return false;
        }
      }

      // If valid submission, add loading indicator to primary submit button
      const submitBtn = form.querySelector('button[type="submit"]:not(.no-spinner)');
      if (submitBtn && !submitBtn.classList.contains('is-loading')) {
        submitBtn.classList.add('is-loading');
        const origText = submitBtn.innerHTML;
        submitBtn.setAttribute('data-original-text', origText);
        submitBtn.innerHTML = `<span class="btn-spinner"></span><span>Processing...</span>`;

        // Safety timeout to reset in case page doesn't reload
        setTimeout(() => {
          submitBtn.classList.remove('is-loading');
          if (submitBtn.getAttribute('data-original-text')) {
            submitBtn.innerHTML = submitBtn.getAttribute('data-original-text');
          }
        }, 10000);
      }
    });
  });

  // 5. Dynamic Client-Side Table Sorting
  initDynamicTableSorting();

  // 6. Instant Table Filter
  initTableInstantSearch();

  // 7. Auto Flash Message Toast Notification Trigger
  const flashToastElem = document.getElementById('flash-toast-trigger');
  if (flashToastElem) {
    const flashSuccess = flashToastElem.getAttribute('data-success');
    const flashError = flashToastElem.getAttribute('data-error');
    if (flashSuccess) {
      window.showToast(flashSuccess, 'success', 'Operation Completed');
    }
    if (flashError) {
      window.showToast(flashError, 'error', 'Error Alert');
    }
  }

  // 8. Chart.js Dashboard Charts Initialization
  initDashboardCharts();
});

/**
 * Dynamic Table Sorting Utility
 */
function initDynamicTableSorting() {
  document.querySelectorAll('table').forEach(table => {
    const headers = table.querySelectorAll('th.sortable, th[data-sort]');
    if (!headers.length) return;

    headers.forEach((th, colIdx) => {
      if (!th.querySelector('.sort-icon')) {
        const icon = document.createElement('i');
        icon.className = 'fa-solid fa-sort sort-icon';
        th.appendChild(icon);
      }

      th.addEventListener('click', function () {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const dataRows = rows.filter(r => r.querySelectorAll('td').length > 1);
        if (dataRows.length <= 1) return;

        const currentAsc = th.classList.contains('sorted-asc');
        const newAsc = !currentAsc;

        headers.forEach(h => {
          h.classList.remove('sorted-asc', 'sorted-desc');
          const ic = h.querySelector('.sort-icon');
          if (ic) ic.className = 'fa-solid fa-sort sort-icon';
        });

        th.classList.add(newAsc ? 'sorted-asc' : 'sorted-desc');
        const currentIcon = th.querySelector('.sort-icon');
        if (currentIcon) {
          currentIcon.className = newAsc ? 'fa-solid fa-sort-up sort-icon' : 'fa-solid fa-sort-down sort-icon';
        }

        dataRows.sort((a, b) => {
          const aCell = a.children[colIdx];
          const bCell = b.children[colIdx];
          if (!aCell || !bCell) return 0;

          const aVal = aCell.innerText.trim();
          const bVal = bCell.innerText.trim();

          // Try numeric sort
          const aNum = parseFloat(aVal.replace(/[^0-9.-]+/g, ''));
          const bNum = parseFloat(bVal.replace(/[^0-9.-]+/g, ''));

          if (!isNaN(aNum) && !isNaN(bNum) && aVal.match(/^[$₹\d.,\s+-]+$/)) {
            return newAsc ? aNum - bNum : bNum - aNum;
          }

          // Try date sort
          const aDate = Date.parse(aVal);
          const bDate = Date.parse(bVal);
          if (!isNaN(aDate) && !isNaN(bDate) && aVal.length > 5 && isNaN(aVal)) {
            return newAsc ? aDate - bDate : bDate - aDate;
          }

          // String sort
          return newAsc 
            ? aVal.localeCompare(bVal, undefined, { numeric: true, sensitivity: 'base' })
            : bVal.localeCompare(aVal, undefined, { numeric: true, sensitivity: 'base' });
        });

        dataRows.forEach(row => tbody.appendChild(row));
      });
    });
  });
}

/**
 * Instant Client-Side Table Filter Utility
 */
function initTableInstantSearch() {
  const searchInputs = document.querySelectorAll('#table-instant-search, [data-table-filter]');
  searchInputs.forEach(input => {
    const targetTableSelector = input.getAttribute('data-table-filter') || 'table';
    const table = document.querySelector(targetTableSelector);
    if (!table) return;

    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    input.addEventListener('input', function () {
      const term = this.value.toLowerCase().trim();
      const rows = tbody.querySelectorAll('tr');

      rows.forEach(row => {
        if (row.querySelectorAll('td').length <= 1) return;
        const text = row.innerText.toLowerCase();
        if (!term || text.includes(term)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });
}

/**
 * Chart.js Dashboard Charts Initialization
 */
function initDashboardCharts() {
  if (typeof Chart === 'undefined') return;

  // Chart 1: Stock Movement & Sales Velocity Area Chart
  const trendCanvas = document.getElementById('stockTrendsChart');
  if (trendCanvas && window.dashboardChartData) {
    const data = window.dashboardChartData.activityTrends || {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      stockIn: [0, 0, 0, 0, 0, 0, 0],
      stockOut: [0, 0, 0, 0, 0, 0, 0]
    };

    const ctx = trendCanvas.getContext('2d');
    const gradIn = ctx.createLinearGradient(0, 0, 0, 260);
    gradIn.addColorStop(0, 'rgba(22, 163, 74, 0.35)');
    gradIn.addColorStop(1, 'rgba(22, 163, 74, 0.02)');

    const gradOut = ctx.createLinearGradient(0, 0, 0, 260);
    gradOut.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradOut.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [
          {
            label: 'Stock In (Purchases & Returns)',
            data: data.stockIn,
            borderColor: '#16A34A',
            backgroundColor: gradIn,
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointBackgroundColor: '#16A34A',
            pointRadius: 4,
            pointHoverRadius: 6
          },
          {
            label: 'Stock Out (Dispensary & Sales)',
            data: data.stockOut,
            borderColor: '#3B82F6',
            backgroundColor: gradOut,
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointBackgroundColor: '#3B82F6',
            pointRadius: 4,
            pointHoverRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 10,
              boxHeight: 10,
              usePointStyle: true,
              pointStyle: 'circle',
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11,
                weight: '600'
              }
            }
          },
          tooltip: {
            padding: 10,
            cornerRadius: 10,
            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 'bold' }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: '#F1F5F9' },
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
              color: '#94A3B8'
            }
          },
          x: {
            grid: { display: false },
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
              color: '#94A3B8'
            }
          }
        }
      }
    });
  }

  // Chart 2: Category Distribution Doughnut Chart
  const catCanvas = document.getElementById('categoryDistributionChart');
  if (catCanvas && window.dashboardChartData) {
    const catData = window.dashboardChartData.categoryDistribution || {
      labels: ['General'],
      values: [100]
    };

    const palette = [
      '#16A34A', // Emerald 600
      '#0D9488', // Teal 600
      '#0284C7', // Sky 600
      '#6366F1', // Indigo 500
      '#F59E0B', // Amber 500
      '#EC4899', // Pink 500
      '#8B5CF6'  // Purple 500
    ];

    new Chart(catCanvas, {
      type: 'doughnut',
      data: {
        labels: catData.labels,
        datasets: [{
          data: catData.values,
          backgroundColor: palette.slice(0, catData.labels.length),
          borderWidth: 2,
          borderColor: '#FFFFFF',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              boxHeight: 10,
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 12,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11,
                weight: '500'
              }
            }
          },
          tooltip: {
            padding: 10,
            cornerRadius: 10,
            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
            callbacks: {
              label: function (ctx) {
                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                const val = ctx.parsed;
                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                return ` ${ctx.label}: ${val} units (${pct}%)`;
              }
            }
          }
        },
        cutout: '68%'
      }
    });
  }
}

