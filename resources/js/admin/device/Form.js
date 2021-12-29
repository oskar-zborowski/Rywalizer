import AppForm from '../app-components/Form/AppForm';

Vue.component('device-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                ip:  '' ,
                uuid:  '' ,
                os_name:  '' ,
                os_version:  '' ,
                browser_name:  '' ,
                browser_version:  '' ,
                
            }
        }
    }

});