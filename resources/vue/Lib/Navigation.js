// Navigation to scroll page
export default function Navigation($store) {
    var vm = this;

    this.scrollTo = function(id) {
        var elmnt = document.getElementById(id);
        elmnt.scrollIntoView();
    }
};