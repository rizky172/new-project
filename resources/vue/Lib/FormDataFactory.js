var FormDataFactory = function() {
    this.isArrayOrObject = function(x) {
        return _.isObject(x) || _.isArray(x);
    }

    this._singleDimObjectToArray = function(json, prefix) {
        var temp = [];

        for(var key in json) {
            if(!_.isFunction(json[key])) {
                if(this.isArrayOrObject(json[key]) &&
                        !(json[key] instanceof File)) {

                    // Convert multi dimension object to single array
                    var list = this._singleDimObjectToArray(json[key], prefix + '.' + key);

                    // Merge with temp
                    for(var x in list)
                        temp.push(list[x]);
                } else {
                    temp.push({ key: prefix + '.' + key, value: json[key]});
                }
            }
        }

        return temp;
    };

    /*
     * Parse formdata.test.1 => formdata[test][1]
     */
    this._parseDotKeyToFormDataFormat = function(key) {
        if(key === undefined || key === null)
            return key;

        var list = key.split('.');

        var newKey = '';
        var glue;
        for(var i=0; i<list.length; i++) {
            var key = list[i];
            glue = '][';

            if(i<=0) {
                glue = '[';
            } else if(i == list.length - 1) {
                glue = ']';
            }

            newKey = newKey + key + glue;
        }

        return newKey;
    }

    this.toSingleDimObject = function(json, prefix) {
        var vm = this;

        if(!prefix)
            prefix = 'formdata';

        var list = this._singleDimObjectToArray(json, prefix);

        var temp = _.map(list, function(x) {
            return {
                key: vm._parseDotKeyToFormDataFormat(x.key),
                value: x.value
            };
        });

        return temp;
    };

    this.toFormData = function(json, prefix) {
        var f = new FormData();

        var list = this.toSingleDimObject(json, prefix);
        _.each(list, function(x) {
            f.append(x.key, x.value);
        });

        return f;
    }
}

export default new FormDataFactory;