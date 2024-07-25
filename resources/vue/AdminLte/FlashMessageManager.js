// Extend from vuesax notification to simplify with WeVelope needs
export default function FlashMessageManager($vs) {
    var vm = this;
    this.toastr = toastr;
    this.isLock = false;

    this.lock = function() {
        this.isLock = true;
    };

    this.unlock = function() {
        this.isLock = false;
    };

    this.add = function(msg, isSuccess) {
        if(!this.isLock) {
            if(isSuccess)
                this.toastr.success(msg);
            else
                this.toastr.error(msg);
        }
    }

    this.error = function(msg) {
        this.add(msg, false);
    };

    this.success = function(msg) {
        this.add(msg, true);
    };

    this.warning = function(msg) {
        this.toastr.warning(msg);
    }
}