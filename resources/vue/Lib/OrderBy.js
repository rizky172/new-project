// Create order by for URL and API
export default function OrderBy() {
    this.delimiter = ',';
    this.data = {
        column: null,
        ordered: true // True mean asc, false mean desc
    };

    /*
     * Set column that need to be sorted. This method automatically set
     * default ordered(asc | desc).
     *
     * @params String column name. Ex: name, address, city
     */
    this.setColumn = function(column) {
        if (this.data.column == column) {
            this.data.ordered = !this.data.ordered;
        } else {
            this.data.ordered = true;
        }

        this.data.column = column;
    };

    /*
     * Get class asc or desc based on column name.
     * It would reset when column is changes.
     *
     * @params String column name
     * @return asc | desc | ''(blank)
     */
    this.getClass = function(column) {
        var ordered = '';

        if (this.data.column == column) {
            ordered = this.data.ordered ? 'asc' : 'desc';
        }

        return ordered;
    };

    /*
     * Make string from this.data.
     *
     * @return String Ex: name:asc
     */
    this.toString = function() {
        var column = this.data.column,
            ordered = this.data.ordered;
        var result = null;

        if (column !== null && ordered !== null) {
            result = column + this.delimiter + (ordered ? 'asc' : 'desc');
        }

        return result;
    };

    this.toObject = function() {
        if(this.data.column == null || this.data.column == undefined)
            return null;
        else
            return {
                column: this.data.column,
                ordered: this.data.ordered ? 'asc' : 'desc'
            }
    }

    /*
     *
     */
    this.reset = function(queryString) {
        this.data.column = null;
        this.data.ordered = true;

        if(typeof queryString == 'string') {
            var list = queryString.split(this.delimiter);

            this.data.column = list[0];
            this.data.ordered = list[1] === 'asc' ? true : false;
        }
    }
};