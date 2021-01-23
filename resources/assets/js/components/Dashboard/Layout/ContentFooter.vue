<template>
    <footer class="footer">
        <div class="container-fluid">
            <div class="copyright pull-right">
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

<style>

</style>
