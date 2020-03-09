class DynamicImg {
    constructor(){
        const focusPoint = require('../plugins/jquery.focuspoint.min.js');
        if(document.body.contains(document.querySelector('.img_placeholder.focuspoint'))){
            window.addEventListener("resize", ()=>{
                this.refresh(false);
            });
            window.addEventListener("load", ()=>{
                this.refresh();
            }, false);
            this.refresh();
        }
        if(document.body.contains(document.querySelector('.defer-load'))){
            const deferImgEvt = ()=>{
                this.deferImg();
            }
            window.addEventListener("scroll", deferImgEvt);
            window.addEventListener("resize", deferImgEvt);
            window.addEventListener("load", deferImgEvt);
        }
    }
    refresh(useFocusPoint=true){
        document.querySelectorAll('.focuspoint:not(.defer-load)').forEach((placeholder, index)=>{
            this.triggerImgZoom(placeholder);
            if (useFocusPoint) {
                this.triggerFocusPoint(placeholder);
            }
        });
    }
    triggerImgZoom(placeholder){
        const img = placeholder.querySelector('img');
        let ratio = placeholder.offsetWidth / placeholder.offsetHeight,
        dataField = 'portrait';
        if (ratio >= 1.64) {//middle point between 3:2 and 16:9 --
            dataField = 'landscape';
        }else if (ratio >= 1.25) {//middle point between 1:1 and 3:2 --
            dataField = 'feed';
        }else if (ratio >= 0.83) {//middle point between 2:3 and 1:1 --
            dataField = 'square';
        }
        img.style.transform = 'scale('+img.getAttribute('data-scale-'+dataField)+') translateZ(0)';
    }
    triggerFocusPoint(placeholder){
        if (placeholder.classList.contains('focuspoint')) {
            const img = placeholder.querySelector('img'),
            $placeholder = $(placeholder);
            $placeholder.data('imageW', img.naturalWidth)
            .data('imageH', img.naturalHeight)
            .data('focusX', placeholder.getAttribute('data-focus-x'))
            .data('focusY', placeholder.getAttribute('data-focus-y'));
            $placeholder.focusPoint();
            $placeholder.focusPoint('init');
            $placeholder.focusPoint('adjustFocus');
        }
    }
    deferImg(){
        document.querySelectorAll('.defer-load').forEach((placeholder, index)=>{
            const img = placeholder.querySelector('img'),
            afterDeferImg = (placeholder)=>{
                placeholder.classList.remove('defer-load');
                placeholder.classList.remove('loading');
                /*if(!document.body.contains(document.querySelector('.defer-load'))){
                    window.removeEventListener("scroll", deferImgEvt);
                    window.removeEventListener("resize", deferImgEvt);
                }*/
            }
            if ($(placeholder).percentageOnScreen() >= 25 &&
                !placeholder.classList.contains('loading') &&
                window.getComputedStyle(img).display !== "none"
            ) {
                let imgSrc = img.getAttribute('data-defer-src');
                if (typeof imgSrc != "undefined") {
                    placeholder.classList.add('loading');
                    new Promise((resolve, reject) => {
                        let imgLoader = new Image();
                        imgLoader.addEventListener('load', e => resolve(imgLoader));
                        imgLoader.addEventListener('error', () => {
                            reject(new Error(`Failed to load image's URL: ${imgSrc}`));
                        });
                        imgLoader.src = imgSrc;
                    })
                    .then(newImg => {
                        var attributes = img.attributes;
                        for (var i=0; i < attributes.length; i++) {
                            if (attributes[i].name != 'src' && attributes[i].name !='data-defer-src') {
                                newImg.setAttribute(attributes[i].name, attributes[i].value);
                            }
                        }
                        placeholder.innerHTML = '';
                        placeholder.appendChild(newImg);
                        setTimeout(()=>{
                            if (placeholder.classList.contains('focuspoint') && !placeholder.classList.contains('focuspoint-active')) {
                                this.triggerFocusPoint(placeholder);
                            }
                            afterDeferImg(placeholder);
                        }, 0);
                    })
                    .catch(error => {
                        console.error(error);
                        afterDeferImg(placeholder);
                    });
                }else{
                    afterDeferImg(placeholder);
                }
            }
        });
    }
}
module.exports = DynamicImg;
