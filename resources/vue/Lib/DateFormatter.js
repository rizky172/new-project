DateFormatter = {
    iso: function(date) {
        return moment(date).format('YYYY-MM-DD');
    },

    short: function(date) {
        return moment(date).format('DD-MM-YYYY');
    },

    fullDateTime: function(date) {
        return moment(date).format('DD-MM-YYYY HH:mm:ss');
    },

    fullDateTimeIso: function(date) {
        return moment(date).format('YYYY-MM-DD HH:mm:ss');
    },

    hour: function(date) {
        return moment(date).format('HH');
    },

    minute: function(date) {
        return moment(date).format('mm');
    },

    hourMinute: function(date) {
        return moment(date).format('HH:mm');
    },

    month: function(date) {
        return moment(date).format('M');
    },

    year: function(date) {
        return moment(date).format('YYYY');
    },
}