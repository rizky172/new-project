export default function PriceFormatter() {
    this.formatPrice = function(value) {
        var num = 0;

        if (value) {
            var num_parts = value.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            num = num_parts.join(".");
        }

        return num;
    };
};