// Control right sidebar
import ControlSidebar from './libs/ControlSidebar.js'
// Control left navigation
import Layout from './libs/Layout.js'
// Control clickable icon on left-top. To toggle left naviation
import PushMenu from './libs/PushMenu.js'
import Treeview from './libs/Treeview.js'
import DirectChat from './libs/DirectChat.js'
import TodoList from './libs/TodoList.js'
// import CardWidget from './libs/CardWidget.js'
// import CardRefresh from './libs/CardRefresh.js'
// import Dropdown from './libs/Dropdown.js'
// import Toasts from './libs/Toasts.js'

/*
export default {
  ControlSidebar,
  Layout,
  PushMenu,
  Treeview,
  DirectChat,
  TodoList,
  CardWidget,
  CardRefresh,
  Dropdown,
  Toasts
}
*/
export default {
    callOnce: function() {
        ControlSidebar($);
        Layout.callOnce();
    },

    callOnload: function() {
        Layout.callOnload();
        Treeview.callOnload();
    },

    // This must be called only once
    callTreeview: function() {
        Treeview.callOnce();
    }

    // Layout(jQuery);
    // PushMenu(jQuery);

    // Treeview(jQuery);
    // DirectChat(jQuery);
    // TodoList(jQuery);
    // CardWidget(jQuery);
    // CardRefresh(jQuery);
    // Dropdown(jQuery);
    // Toasts(jQuery);
}