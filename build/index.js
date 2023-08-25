(()=>{"use strict";var e,t={421:()=>{const e=window.wp.blocks;function t(){return t=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},t.apply(this,arguments)}const n=window.wp.element,r=window.wp.i18n,l=window.wp.blockEditor,o=window.wp.components,a=JSON.parse('{"u2":"create-block/twitchcraft"}');(0,e.registerBlockType)(a.u2,{edit:function(e){let{attributes:a,setAttributes:i}=e;const c=(0,l.useBlockProps)(),[s,u]=(0,n.useState)("yes");return(0,n.createElement)("div",c,(0,n.createElement)(l.InspectorControls,null,(0,n.createElement)(o.PanelBody,{title:(0,r.__)("Block settings","twitchcraft")},(0,n.createElement)(o.SelectControl,{label:"Stream Status",value:s,options:[{label:"Live on Twitch",value:"yes"},{label:"Not Live",value:"no"}],onChange:e=>u(e),__nextHasNoMarginBottom:!0}),(0,n.createElement)(o.TextControl,{label:"Username",value:a.userName,onChange:e=>i({userName:e})}))),"yes"===s?(0,n.createElement)("div",null,(0,n.createElement)("h3",null,"Online"),(0,n.createElement)(l.RichText,t({},c,{value:a.liveContent,onChange:e=>i({liveContent:e}),placeholder:(0,r.__)("Add the content to display here.")}))):(0,n.createElement)("div",null,(0,n.createElement)("h3",null,"Offline"),(0,n.createElement)(l.RichText,t({},c,{value:a.offlineContent,onChange:e=>i({offlineContent:e}),placeholder:(0,r.__)("Add the content to display here.")}))))},save:()=>null})}},n={};function r(e){var l=n[e];if(void 0!==l)return l.exports;var o=n[e]={exports:{}};return t[e](o,o.exports,r),o.exports}r.m=t,e=[],r.O=(t,n,l,o)=>{if(!n){var a=1/0;for(u=0;u<e.length;u++){for(var[n,l,o]=e[u],i=!0,c=0;c<n.length;c++)(!1&o||a>=o)&&Object.keys(r.O).every((e=>r.O[e](n[c])))?n.splice(c--,1):(i=!1,o<a&&(a=o));if(i){e.splice(u--,1);var s=l();void 0!==s&&(t=s)}}return t}o=o||0;for(var u=e.length;u>0&&e[u-1][2]>o;u--)e[u]=e[u-1];e[u]=[n,l,o]},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={826:0,431:0};r.O.j=t=>0===e[t];var t=(t,n)=>{var l,o,[a,i,c]=n,s=0;if(a.some((t=>0!==e[t]))){for(l in i)r.o(i,l)&&(r.m[l]=i[l]);if(c)var u=c(r)}for(t&&t(n);s<a.length;s++)o=a[s],r.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return r.O(u)},n=globalThis.webpackChunktwitchcraft=globalThis.webpackChunktwitchcraft||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var l=r.O(void 0,[431],(()=>r(421)));l=r.O(l)})();