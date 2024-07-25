// To manage list of loading by their "key" that pass to this class
// This similar with LoadingService in AngularJS(from older WeVelope project)
export default function FlashMessageManager($store) {
    var vm = this;

    this.$store = $store;
    this.duration = 5 * 1000;

    this.start = function() {
        this.startTimer();
    };

    this.add = function(msg, isError) {
        this.$store.dispatch('flash/add', { message: msg, is_error: isError });
    }

    this.startTimer = function() {
        var vm = this;
        setInterval(function() {
            var messages = vm.$store.state.flash.messages;

            // Validate messages already init
            if(!Array.isArray(messages))
                return;

            var date = new Date();
            var duration = vm.duration; // ms
            for(var i=0; i<messages.length; i++) {
                var x = messages[i];
                // console.log(x, x.date, date);
                if(x.date.getTime() + duration < date.getTime()) {
                    // Remove item x
                    vm.remove(x);
                }
            }
        }, this.duration);
    };

    // Call when messages removed
    this.remove = function(item) {
        vm.$store.dispatch('flash/remove', item);
    };
  }