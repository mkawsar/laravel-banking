export default {
    table: {
        tableClass: "table table-striped table-bordered",
        ascendingIcon: "glyphicon glyphicon-chevron-up",
        descendingIcon: "glyphicon glyphicon-chevron-down",
        handleIcon: "glyphicon glyphicon-menu-hamburger",
        renderIcon: function (classes, options) {
            return `<span class="${classes.join(' ')}"></span>`
        }
    },
    paginationInfo: {
        infoClass: 'pull-left'
    },
    pagination: {
        wrapperClass: "pagination pull-right",
        activeClass: "btn btn-primary",
        disabledClass: "disabled",
        pageClass: "btn btn-default",
        linkClass: "btn btn-default",
        icons: {
            first: "",
            prev: "",
            next: "",
            last: ""
        }
    }
};
