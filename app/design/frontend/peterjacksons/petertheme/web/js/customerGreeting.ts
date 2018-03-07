import $ = require("jquery");
import customerData = require("Magento_Customer/js/customer-data");

const module = (config, element) => {
    let firstname = customerData.get('customer')().firstname;
    if (typeof(firstname) === "undefined") {
        customerData.reload('customer');
    }
    const check = setInterval(() => {
        let firstname = customerData.get('customer')().firstname;
        if (firstname) {
            $(element).text(firstname);
            clearInterval(check);
        } else {
            $(element).parent().find('.header-left__account-popup').remove();
        }
    }, 500);

};

export = module;