export default function DateFormat() {
    this.iso = function(date) {
        return moment(date).format('YYYY-MM-DD');
    };

    this.short = function(date) {
        return moment(date).format('DD-MM-YYYY');
    };

    this.fullDateTime = function(date) {
        return moment(date).format('DD-MM-YYYY HH:mm:ss');
    };

    this.fullDateTimeIso = function(date) {
        return moment(date).format('YYYY-MM-DD HH:mm:ss');
    };

    this.hour = function(date) {
        return moment(date).format('HH');
    };

    this.minute = function(date) {
        return moment(date).format('mm');
    };

    this.hourMinute = function(date) {
        return moment(date).format('HH:mm');
    };

    this.month = function(date) {
        return moment(date).format('M');
    };

    this.year = function(date) {
        return moment(date).format('YYYY');
    };
};