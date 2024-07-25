/* Extended Math class method
 */

// Generate random unique hash
// @return String
Math.getRandomHash = function() {
    var s1, s2;
    s1 = String(Math.floor(Math.random()*1000));
    s2 = String(new Date().getTime());
    return s1 + s2;
};

Array.prototype.clear = function() {
    while(this.length > 0) {
        this.pop();
    }
};