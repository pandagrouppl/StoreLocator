import "slick";
import $ = require("jquery");

export class OurMills {

    constructor() {
        this._youtubeOverlay();
        this._millsMenu();
    }

    _youtubeOverlay(): void {

        const tag = document.createElement('script');
        tag.src = 'https://www.youtube.com/iframe_api';
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    _millsMenu(): void {
        const acitveMenu = 'about-us-wrapper__mills-menu--active';
        const activeLi = 'about-us-wrapper__mills-flexbox--active';
        const menu = $('.about-us-wrapper__mills-menu li');
        const Li = $('.about-us-wrapper__mills-flexbox');

        menu.click((event) => {
            if (event.target.className != acitveMenu) {
                menu.toggleClass(acitveMenu);
                Li.toggleClass(activeLi);
            }
        });
        }
}
