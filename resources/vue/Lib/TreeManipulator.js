/* Manipulate tree structure data, this class would remove or add without
 * destroying the references
 *
 * @params Array Array of node object that contains childs.
 * Here the structure example:
 * [
 *  {
 *      id: 1,
 *      name: 'Node 1',
 *      childs: [
 *          node, node, node
 *      ]
 *  },
 *  node
 * ]
 */
var TreeManipulator = function(treeObj) {
    this.tree = treeObj;

    this.findById = function(id) {
        var found = null;

        for(var i=0; i<this.tree.length; i++) {
            var x = this.tree[i];
            found = this._findById(x, id);

            if(found !== null)
                break;
        }

        return found;
    };

    this.findParentByChildId = function(childId)
    {
        var found = null;

        for(var i=0; i<this.tree.length; i++) {
            var x = this.tree[i];

            found = this._findById(x, childId, null, true);

            if(found !== null)
                break;
        }

        return found;
    }

    this.removeById = function(id) {
        var found = this.findParentByChildId(id);

        if(found)
            found.childs = _.reject(found.childs, function(x) {
                return x.id == id;
            });
        else
            this.tree = _.reject(this.tree, function(x) {
                return x.id == id;
            });
    };

    this.add = function(node, parent) {
        if(parent == null)
            this.tree.push(node);
        else
            parent.childs.push(node);
    }

    this.addByParentId = function(node, parentId) {
        var parent = this.findById(parentId);

        this.add(node, parent);
    }

    // Walking in tree Recursively
    this._findById = function(node, id, parent, returnParent) {
        var found = null;
        if(node.id == id) {
            // debugger;
            if(returnParent === undefined || !returnParent)
                found = node;
            else
                found = parent;
        } else {
            // Recursive
            if(_.isArray(node.childs)) {
                for(var i=0; i<node.childs.length; i++) {
                    var x = node.childs[i];
                    if(_.isObject(x))
                        found = this._findById(x, id, node, returnParent);
                    
                    // If found, just break the loop
                    if(found !== null) {
                        break;
                    }
                }
            }
        }

        return found;
    }
}

export default TreeManipulator;