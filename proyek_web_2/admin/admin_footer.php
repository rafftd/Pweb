</div> <!-- Penutup untuk .main-content yang ada di header -->
    <footer class="admin-footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> Nusantara Cuisine Admin Panel</p>
        </div>
    </footer>
    
    <!-- JavaScript untuk admin panel -->
    <script>
        // Toggle sidebar
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 600);
            });
        }, 5000);
    </script>
</body>
</html>