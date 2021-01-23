import Vue from 'vue';

var pluralize = function (word, amount) {
    if (amount === 1 || amount === 0) {
        return word;
    } else {
        return `${word}s`;
    }
}
Vue.filter('pluralize', pluralize);
