// To manage list of loading by their "key" that pass to this class
// This similar with LoadingService in AngularJS(from older WeVelope project)
export default function VuesaxLoadingManager($vs) {
    var vm = this;

    this.$vs = $vs;

    // Turn on loading to and element
    this.on = function(key) {
        this.$vs.loading({
            // background: 'primary',
            // color: '#fff',
            container: "." + key,
            scale: 0.45
        });

        // console.log('off');
        // this.set(key, false);
    };

    // Turn off loading to and element
    this.off = function(key) {
        this.$vs.loading.close("." + key + " > .con-vs-loading")

        // console.log('on');
        // this.set(key, true);
    };
};