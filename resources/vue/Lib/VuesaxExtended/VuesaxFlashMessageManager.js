// Extend from vuesax notification to simplify with WeVelope needs
export default function VuesaxFlashMessageManager($vs) {
    var vm = this;
    this.$vs = $vs;

    this.add = function(msg, isSuccess) {
        this.$vs.notify({
            title: isSuccess ? 'Success' : 'Error!',
            text: msg,
            color: isSuccess ? 'success' : 'danger',
            time: isSuccess ? 5000 : 10000
        });
    }

    this.error = function(msg) {
        this.add(msg, false);
    };

    this.success = function(msg) {
        this.add(msg, true);
    };
}