        <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var username = localStorage.getItem("username");
                        if (username && !window.location.search.includes("who=")) {
                            window.location.href = window.location.pathname + "?who=" + encodeURIComponent(username);
                        }
                    });
        </script>
