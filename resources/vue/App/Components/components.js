// Create global component

import Vue from 'vue';
// import _wvList from './WvFormBuilder/components.js';
import Download from './Download.vue';

var list = [
    Download
];
// console.log('list', list);
for(var i=0; i<list.length; i++) {
    Vue.component(list[i].name, list[i]);
}

// list = _wvList;
// console.log('wevelope', list);
// for(var i=0; i<list.length; i++) {
//     Vue.component(list[i].name, list[i]);
// }