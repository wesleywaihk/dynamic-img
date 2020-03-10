import DynamicImg from './app/dynamic-img';
window.dynamicImg = new DynamicImg();

//trigger defure img detact and load 
dynamicImg.deferImg();

//trigger focus point change 
dynamicImg.triggerFocusPoint( $el );
