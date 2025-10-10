<footer class="footer">
  <p>© 2025 Melofi | All Rights Reserved</p>
</footer>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const toggleButton = document.querySelector(".sidebar-toggle");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector("main.app-content");

  if (toggleButton && sidebar && mainContent) {
    toggleButton.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      mainContent.classList.toggle("collapsed");
    });
  }
});
</script>
</body>
</html>