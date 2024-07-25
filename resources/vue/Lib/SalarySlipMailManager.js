/*
 * This class would be help which ID that has added or not
 *
 * @params all added invoices that would be sent
 */
export default function SalarySlipMailManager(invoices) {
    var vm = this;

    this.invoices = invoices;

    // Add new invoice in this object if not exists
    //
    // @params Object Invoice that want to added
    this.add = function(invoice) {
        var found = _.findWhere(this.invoices, { id: invoice.id });

        // If found you don't need to add to list
        if(!found) {
            this.invoices.push(invoice);
        }
    }

    // Remove an invoice in this object if found
    //
    // @param invoice with correct id
    this.remove = function(invoice) {
        this.invoices = _.filter(this.invoices, function(x) {
            return x.id != invoice.id;
        })
    }

    // Generate param invoices is_chacked based on this.invoices
    this.generateCheckUnchecked = function(invoices) {
        var temp = _.map(invoices, function(x) {
            var found = _.findWhere(vm.invoices, { id: x.id });

            x.is_checked = found ? 1 : 0;

            return x;
        });

        return temp;
    }

    this.getInvoices = function() {
        return this.invoices;
    }
};