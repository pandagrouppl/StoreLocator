import $ = require("jquery");
import submitForm = require("js/submitFormShowPopup");

const careers = (config, element) =>  {

    const addFile = $('#add-file');
    if ($(addFile).length) {
        $(addFile).change((e) => {
            $('#career-form-file-name').show().text(e.target.files["0"].name);
        });
    }
    submitForm(config,element);
};

export = careers;