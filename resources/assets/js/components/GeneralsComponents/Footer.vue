<template>
    <footer class="footer footer-transparent">
        <div class="container">
            <div class="copyright">
                Copyright &copy; {{date}} msrssl
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
        data: function () {
            return {
                date: new Date().getFullYear(),
            }
        }
    }
</script>
