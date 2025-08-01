/**
 * File: chart.js
 * Purpose: Renders progress donut chart and manages achievements reveal for CodeGaming dashboard/home page.
 * Features:
 *   - Displays completion percentage using Chart.js doughnut chart.
 *   - Uses CSS variables for dynamic chart colors.
 *   - Supports animated chart rendering and legend control.
 * Usage:
 *   - Included on pages with progress visualization (dashboard, home, profile).
 *   - Requires Chart.js library and an HTML canvas element with id 'completionChart'.
 * Included Files/Dependencies:
 *   - Chart.js
 *   - CSS variables for chart color
 * Author: CodeGaming Team
 * Last Updated: July 22, 2025
 */

// ─────────────────────────────────────────────────────────
// 7️⃣ Progress Chart & Achievements Reveal
// ─────────────────────────────────────────────────────────

// A) Chart.js Donut Chart
const ctx = document.getElementById('completionChart').getContext('2d');
new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ['Completed', 'Remaining'],
    datasets: [{
      data: [75, 25],           // placeholder percentages
      backgroundColor: [
        getComputedStyle(document.documentElement).getPropertyValue('--chart-color').trim(),
        'rgba(255,255,255,0.1)'
      ],
      borderWidth: 0
    }]
  },
  options: {
    cutout: '70%',
    plugins: { legend: { display: false } },
    animation: { animateRotate: true, duration: 1000 }
  }
});