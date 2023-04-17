/*!
FullCalendar Interaction Plugin v6.1.5
Docs & License: https://fullcalendar.io/docs/editable
(c) 2023 Adam Shaw
*/
FullCalendar.Interaction=function(t,e,i){"use strict";i.config.touchMouseIgnoreWait=500;let n=0,s=0,r=!1;class o{constructor(t){this.subjectEl=null,this.selector="",this.handleSelector="",this.shouldIgnoreMove=!1,this.shouldWatchScroll=!0,this.isDragging=!1,this.isTouchDragging=!1,this.wasTouchScroll=!1,this.handleMouseDown=t=>{if(!this.shouldIgnoreMouse()&&function(t){return 0===t.button&&!t.ctrlKey}(t)&&this.tryStart(t)){let e=this.createEventFromMouse(t,!0);this.emitter.trigger("pointerdown",e),this.initScrollWatch(e),this.shouldIgnoreMove||document.addEventListener("mousemove",this.handleMouseMove),document.addEventListener("mouseup",this.handleMouseUp)}},this.handleMouseMove=t=>{let e=this.createEventFromMouse(t);this.recordCoords(e),this.emitter.trigger("pointermove",e)},this.handleMouseUp=t=>{document.removeEventListener("mousemove",this.handleMouseMove),document.removeEventListener("mouseup",this.handleMouseUp),this.emitter.trigger("pointerup",this.createEventFromMouse(t)),this.cleanup()},this.handleTouchStart=t=>{if(this.tryStart(t)){this.isTouchDragging=!0;let e=this.createEventFromTouch(t,!0);this.emitter.trigger("pointerdown",e),this.initScrollWatch(e);let i=t.target;this.shouldIgnoreMove||i.addEventListener("touchmove",this.handleTouchMove),i.addEventListener("touchend",this.handleTouchEnd),i.addEventListener("touchcancel",this.handleTouchEnd),window.addEventListener("scroll",this.handleTouchScroll,!0)}},this.handleTouchMove=t=>{let e=this.createEventFromTouch(t);this.recordCoords(e),this.emitter.trigger("pointermove",e)},this.handleTouchEnd=t=>{if(this.isDragging){let e=t.target;e.removeEventListener("touchmove",this.handleTouchMove),e.removeEventListener("touchend",this.handleTouchEnd),e.removeEventListener("touchcancel",this.handleTouchEnd),window.removeEventListener("scroll",this.handleTouchScroll,!0),this.emitter.trigger("pointerup",this.createEventFromTouch(t)),this.cleanup(),this.isTouchDragging=!1,n+=1,setTimeout(()=>{n-=1},i.config.touchMouseIgnoreWait)}},this.handleTouchScroll=()=>{this.wasTouchScroll=!0},this.handleScroll=t=>{if(!this.shouldIgnoreMove){let e=window.pageXOffset-this.prevScrollX+this.prevPageX,i=window.pageYOffset-this.prevScrollY+this.prevPageY;this.emitter.trigger("pointermove",{origEvent:t,isTouch:this.isTouchDragging,subjectEl:this.subjectEl,pageX:e,pageY:i,deltaX:e-this.origPageX,deltaY:i-this.origPageY})}},this.containerEl=t,this.emitter=new i.Emitter,t.addEventListener("mousedown",this.handleMouseDown),t.addEventListener("touchstart",this.handleTouchStart,{passive:!0}),s+=1,1===s&&window.addEventListener("touchmove",l,{passive:!1})}destroy(){this.containerEl.removeEventListener("mousedown",this.handleMouseDown),this.containerEl.removeEventListener("touchstart",this.handleTouchStart,{passive:!0}),s-=1,s||window.removeEventListener("touchmove",l,{passive:!1})}tryStart(t){let e=this.querySubjectEl(t),n=t.target;return!(!e||this.handleSelector&&!i.elementClosest(n,this.handleSelector))&&(this.subjectEl=e,this.isDragging=!0,this.wasTouchScroll=!1,!0)}cleanup(){r=!1,this.isDragging=!1,this.subjectEl=null,this.destroyScrollWatch()}querySubjectEl(t){return this.selector?i.elementClosest(t.target,this.selector):this.containerEl}shouldIgnoreMouse(){return n||this.isTouchDragging}cancelTouchScroll(){this.isDragging&&(r=!0)}initScrollWatch(t){this.shouldWatchScroll&&(this.recordCoords(t),window.addEventListener("scroll",this.handleScroll,!0))}recordCoords(t){this.shouldWatchScroll&&(this.prevPageX=t.pageX,this.prevPageY=t.pageY,this.prevScrollX=window.pageXOffset,this.prevScrollY=window.pageYOffset)}destroyScrollWatch(){this.shouldWatchScroll&&window.removeEventListener("scroll",this.handleScroll,!0)}createEventFromMouse(t,e){let i=0,n=0;return e?(this.origPageX=t.pageX,this.origPageY=t.pageY):(i=t.pageX-this.origPageX,n=t.pageY-this.origPageY),{origEvent:t,isTouch:!1,subjectEl:this.subjectEl,pageX:t.pageX,pageY:t.pageY,deltaX:i,deltaY:n}}createEventFromTouch(t,e){let i,n,s=t.touches,r=0,o=0;return s&&s.length?(i=s[0].pageX,n=s[0].pageY):(i=t.pageX,n=t.pageY),e?(this.origPageX=i,this.origPageY=n):(r=i-this.origPageX,o=n-this.origPageY),{origEvent:t,isTouch:!0,subjectEl:this.subjectEl,pageX:i,pageY:n,deltaX:r,deltaY:o}}}function l(t){r&&t.preventDefault()}class a{constructor(){this.isVisible=!1,this.sourceEl=null,this.mirrorEl=null,this.sourceElRect=null,this.parentNode=document.body,this.zIndex=9999,this.revertDuration=0}start(t,e,i){this.sourceEl=t,this.sourceElRect=this.sourceEl.getBoundingClientRect(),this.origScreenX=e-window.pageXOffset,this.origScreenY=i-window.pageYOffset,this.deltaX=0,this.deltaY=0,this.updateElPosition()}handleMove(t,e){this.deltaX=t-window.pageXOffset-this.origScreenX,this.deltaY=e-window.pageYOffset-this.origScreenY,this.updateElPosition()}setIsVisible(t){t?this.isVisible||(this.mirrorEl&&(this.mirrorEl.style.display=""),this.isVisible=t,this.updateElPosition()):this.isVisible&&(this.mirrorEl&&(this.mirrorEl.style.display="none"),this.isVisible=t)}stop(t,e){let i=()=>{this.cleanup(),e()};t&&this.mirrorEl&&this.isVisible&&this.revertDuration&&(this.deltaX||this.deltaY)?this.doRevertAnimation(i,this.revertDuration):setTimeout(i,0)}doRevertAnimation(t,e){let n=this.mirrorEl,s=this.sourceEl.getBoundingClientRect();n.style.transition="top "+e+"ms,left "+e+"ms",i.applyStyle(n,{left:s.left,top:s.top}),i.whenTransitionDone(n,()=>{n.style.transition="",t()})}cleanup(){this.mirrorEl&&(i.removeElement(this.mirrorEl),this.mirrorEl=null),this.sourceEl=null}updateElPosition(){this.sourceEl&&this.isVisible&&i.applyStyle(this.getMirrorEl(),{left:this.sourceElRect.left+this.deltaX,top:this.sourceElRect.top+this.deltaY})}getMirrorEl(){let t=this.sourceElRect,e=this.mirrorEl;return e||(e=this.mirrorEl=this.sourceEl.cloneNode(!0),e.style.userSelect="none",e.classList.add("fc-event-dragging"),i.applyStyle(e,{position:"fixed",zIndex:this.zIndex,visibility:"",boxSizing:"border-box",width:t.right-t.left,height:t.bottom-t.top,right:"auto",bottom:"auto",margin:0}),this.parentNode.appendChild(e)),e}}class h extends i.ScrollController{constructor(t,e){super(),this.handleScroll=()=>{this.scrollTop=this.scrollController.getScrollTop(),this.scrollLeft=this.scrollController.getScrollLeft(),this.handleScrollChange()},this.scrollController=t,this.doesListening=e,this.scrollTop=this.origScrollTop=t.getScrollTop(),this.scrollLeft=this.origScrollLeft=t.getScrollLeft(),this.scrollWidth=t.getScrollWidth(),this.scrollHeight=t.getScrollHeight(),this.clientWidth=t.getClientWidth(),this.clientHeight=t.getClientHeight(),this.clientRect=this.computeClientRect(),this.doesListening&&this.getEventTarget().addEventListener("scroll",this.handleScroll)}destroy(){this.doesListening&&this.getEventTarget().removeEventListener("scroll",this.handleScroll)}getScrollTop(){return this.scrollTop}getScrollLeft(){return this.scrollLeft}setScrollTop(t){this.scrollController.setScrollTop(t),this.doesListening||(this.scrollTop=Math.max(Math.min(t,this.getMaxScrollTop()),0),this.handleScrollChange())}setScrollLeft(t){this.scrollController.setScrollLeft(t),this.doesListening||(this.scrollLeft=Math.max(Math.min(t,this.getMaxScrollLeft()),0),this.handleScrollChange())}getClientWidth(){return this.clientWidth}getClientHeight(){return this.clientHeight}getScrollWidth(){return this.scrollWidth}getScrollHeight(){return this.scrollHeight}handleScrollChange(){}}class c extends h{constructor(t,e){super(new i.ElementScrollController(t),e)}getEventTarget(){return this.scrollController.el}computeClientRect(){return i.computeInnerRect(this.scrollController.el)}}class d extends h{constructor(t){super(new i.WindowScrollController,t)}getEventTarget(){return window}computeClientRect(){return{left:this.scrollLeft,right:this.scrollLeft+this.clientWidth,top:this.scrollTop,bottom:this.scrollTop+this.clientHeight}}handleScrollChange(){this.clientRect=this.computeClientRect()}}const g="function"==typeof performance?performance.now:Date.now;class u{constructor(){this.isEnabled=!0,this.scrollQuery=[window,".fc-scroller"],this.edgeThreshold=50,this.maxVelocity=300,this.pointerScreenX=null,this.pointerScreenY=null,this.isAnimating=!1,this.scrollCaches=null,this.everMovedUp=!1,this.everMovedDown=!1,this.everMovedLeft=!1,this.everMovedRight=!1,this.animate=()=>{if(this.isAnimating){let t=this.computeBestEdge(this.pointerScreenX+window.pageXOffset,this.pointerScreenY+window.pageYOffset);if(t){let e=g();this.handleSide(t,(e-this.msSinceRequest)/1e3),this.requestAnimation(e)}else this.isAnimating=!1}}}start(t,e,i){this.isEnabled&&(this.scrollCaches=this.buildCaches(i),this.pointerScreenX=null,this.pointerScreenY=null,this.everMovedUp=!1,this.everMovedDown=!1,this.everMovedLeft=!1,this.everMovedRight=!1,this.handleMove(t,e))}handleMove(t,e){if(this.isEnabled){let i=t-window.pageXOffset,n=e-window.pageYOffset,s=null===this.pointerScreenY?0:n-this.pointerScreenY,r=null===this.pointerScreenX?0:i-this.pointerScreenX;s<0?this.everMovedUp=!0:s>0&&(this.everMovedDown=!0),r<0?this.everMovedLeft=!0:r>0&&(this.everMovedRight=!0),this.pointerScreenX=i,this.pointerScreenY=n,this.isAnimating||(this.isAnimating=!0,this.requestAnimation(g()))}}stop(){if(this.isEnabled){this.isAnimating=!1;for(let t of this.scrollCaches)t.destroy();this.scrollCaches=null}}requestAnimation(t){this.msSinceRequest=t,requestAnimationFrame(this.animate)}handleSide(t,e){let{scrollCache:i}=t,{edgeThreshold:n}=this,s=n-t.distance,r=s*s/(n*n)*this.maxVelocity*e,o=1;switch(t.name){case"left":o=-1;case"right":i.setScrollLeft(i.getScrollLeft()+r*o);break;case"top":o=-1;case"bottom":i.setScrollTop(i.getScrollTop()+r*o)}}computeBestEdge(t,e){let{edgeThreshold:i}=this,n=null,s=this.scrollCaches||[];for(let r of s){let s=r.clientRect,o=t-s.left,l=s.right-t,a=e-s.top,h=s.bottom-e;o>=0&&l>=0&&a>=0&&h>=0&&(a<=i&&this.everMovedUp&&r.canScrollUp()&&(!n||n.distance>a)&&(n={scrollCache:r,name:"top",distance:a}),h<=i&&this.everMovedDown&&r.canScrollDown()&&(!n||n.distance>h)&&(n={scrollCache:r,name:"bottom",distance:h}),o<=i&&this.everMovedLeft&&r.canScrollLeft()&&(!n||n.distance>o)&&(n={scrollCache:r,name:"left",distance:o}),l<=i&&this.everMovedRight&&r.canScrollRight()&&(!n||n.distance>l)&&(n={scrollCache:r,name:"right",distance:l}))}return n}buildCaches(t){return this.queryScrollEls(t).map(t=>t===window?new d(!1):new c(t,!1))}queryScrollEls(t){let e=[];for(let i of this.scrollQuery)"object"==typeof i?e.push(i):e.push(...Array.prototype.slice.call(t.getRootNode().querySelectorAll(i)));return e}}class p extends i.ElementDragging{constructor(t,e){super(t),this.containerEl=t,this.delay=null,this.minDistance=0,this.touchScrollAllowed=!0,this.mirrorNeedsRevert=!1,this.isInteracting=!1,this.isDragging=!1,this.isDelayEnded=!1,this.isDistanceSurpassed=!1,this.delayTimeoutId=null,this.onPointerDown=t=>{this.isDragging||(this.isInteracting=!0,this.isDelayEnded=!1,this.isDistanceSurpassed=!1,i.preventSelection(document.body),i.preventContextMenu(document.body),t.isTouch||t.origEvent.preventDefault(),this.emitter.trigger("pointerdown",t),this.isInteracting&&!this.pointer.shouldIgnoreMove&&(this.mirror.setIsVisible(!1),this.mirror.start(t.subjectEl,t.pageX,t.pageY),this.startDelay(t),this.minDistance||this.handleDistanceSurpassed(t)))},this.onPointerMove=t=>{if(this.isInteracting){if(this.emitter.trigger("pointermove",t),!this.isDistanceSurpassed){let e,i=this.minDistance,{deltaX:n,deltaY:s}=t;e=n*n+s*s,e>=i*i&&this.handleDistanceSurpassed(t)}this.isDragging&&("scroll"!==t.origEvent.type&&(this.mirror.handleMove(t.pageX,t.pageY),this.autoScroller.handleMove(t.pageX,t.pageY)),this.emitter.trigger("dragmove",t))}},this.onPointerUp=t=>{this.isInteracting&&(this.isInteracting=!1,i.allowSelection(document.body),i.allowContextMenu(document.body),this.emitter.trigger("pointerup",t),this.isDragging&&(this.autoScroller.stop(),this.tryStopDrag(t)),this.delayTimeoutId&&(clearTimeout(this.delayTimeoutId),this.delayTimeoutId=null))};let n=this.pointer=new o(t);n.emitter.on("pointerdown",this.onPointerDown),n.emitter.on("pointermove",this.onPointerMove),n.emitter.on("pointerup",this.onPointerUp),e&&(n.selector=e),this.mirror=new a,this.autoScroller=new u}destroy(){this.pointer.destroy(),this.onPointerUp({})}startDelay(t){"number"==typeof this.delay?this.delayTimeoutId=setTimeout(()=>{this.delayTimeoutId=null,this.handleDelayEnd(t)},this.delay):this.handleDelayEnd(t)}handleDelayEnd(t){this.isDelayEnded=!0,this.tryStartDrag(t)}handleDistanceSurpassed(t){this.isDistanceSurpassed=!0,this.tryStartDrag(t)}tryStartDrag(t){this.isDelayEnded&&this.isDistanceSurpassed&&(this.pointer.wasTouchScroll&&!this.touchScrollAllowed||(this.isDragging=!0,this.mirrorNeedsRevert=!1,this.autoScroller.start(t.pageX,t.pageY,this.containerEl),this.emitter.trigger("dragstart",t),!1===this.touchScrollAllowed&&this.pointer.cancelTouchScroll()))}tryStopDrag(t){this.mirror.stop(this.mirrorNeedsRevert,this.stopDrag.bind(this,t))}stopDrag(t){this.isDragging=!1,this.emitter.trigger("dragend",t)}setIgnoreMove(t){this.pointer.shouldIgnoreMove=t}setMirrorIsVisible(t){this.mirror.setIsVisible(t)}setMirrorNeedsRevert(t){this.mirrorNeedsRevert=t}setAutoScrollEnabled(t){this.autoScroller.isEnabled=t}}class v{constructor(t){this.origRect=i.computeRect(t),this.scrollCaches=i.getClippingParents(t).map(t=>new c(t,!0))}destroy(){for(let t of this.scrollCaches)t.destroy()}computeLeft(){let t=this.origRect.left;for(let e of this.scrollCaches)t+=e.origScrollLeft-e.getScrollLeft();return t}computeTop(){let t=this.origRect.top;for(let e of this.scrollCaches)t+=e.origScrollTop-e.getScrollTop();return t}isWithinClipping(t,e){let n={left:t,top:e};for(let t of this.scrollCaches)if(!E(t.getEventTarget())&&!i.pointInsideRect(n,t.clientRect))return!1;return!0}}function E(t){let e=t.tagName;return"HTML"===e||"BODY"===e}class m{constructor(t,e){this.useSubjectCenter=!1,this.requireInitial=!0,this.initialHit=null,this.movingHit=null,this.finalHit=null,this.handlePointerDown=t=>{let{dragging:e}=this;this.initialHit=null,this.movingHit=null,this.finalHit=null,this.prepareHits(),this.processFirstCoord(t),this.initialHit||!this.requireInitial?(e.setIgnoreMove(!1),this.emitter.trigger("pointerdown",t)):e.setIgnoreMove(!0)},this.handleDragStart=t=>{this.emitter.trigger("dragstart",t),this.handleMove(t,!0)},this.handleDragMove=t=>{this.emitter.trigger("dragmove",t),this.handleMove(t)},this.handlePointerUp=t=>{this.releaseHits(),this.emitter.trigger("pointerup",t)},this.handleDragEnd=t=>{this.movingHit&&this.emitter.trigger("hitupdate",null,!0,t),this.finalHit=this.movingHit,this.movingHit=null,this.emitter.trigger("dragend",t)},this.droppableStore=e,t.emitter.on("pointerdown",this.handlePointerDown),t.emitter.on("dragstart",this.handleDragStart),t.emitter.on("dragmove",this.handleDragMove),t.emitter.on("pointerup",this.handlePointerUp),t.emitter.on("dragend",this.handleDragEnd),this.dragging=t,this.emitter=new i.Emitter}processFirstCoord(t){let e,n={left:t.pageX,top:t.pageY},s=n,r=t.subjectEl;r instanceof HTMLElement&&(e=i.computeRect(r),s=i.constrainPoint(s,e));let o=this.initialHit=this.queryHitForOffset(s.left,s.top);if(o){if(this.useSubjectCenter&&e){let t=i.intersectRects(e,o.rect);t&&(s=i.getRectCenter(t))}this.coordAdjust=i.diffPoints(s,n)}else this.coordAdjust={left:0,top:0}}handleMove(t,e){let i=this.queryHitForOffset(t.pageX+this.coordAdjust.left,t.pageY+this.coordAdjust.top);!e&&S(this.movingHit,i)||(this.movingHit=i,this.emitter.trigger("hitupdate",i,!1,t))}prepareHits(){this.offsetTrackers=i.mapHash(this.droppableStore,t=>(t.component.prepareHits(),new v(t.el)))}releaseHits(){let{offsetTrackers:t}=this;for(let e in t)t[e].destroy();this.offsetTrackers={}}queryHitForOffset(t,e){let{droppableStore:n,offsetTrackers:s}=this,r=null;for(let o in n){let l=n[o].component,a=s[o];if(a&&a.isWithinClipping(t,e)){let n=a.computeLeft(),s=a.computeTop(),h=t-n,c=e-s,{origRect:d}=a,g=d.right-d.left,u=d.bottom-d.top;if(h>=0&&h<g&&c>=0&&c<u){let t=l.queryHit(h,c,g,u);t&&i.rangeContainsRange(t.dateProfile.activeRange,t.dateSpan.range)&&(!r||t.layer>r.layer)&&(t.componentId=o,t.context=l.context,t.rect.left+=n,t.rect.right+=n,t.rect.top+=s,t.rect.bottom+=s,r=t)}}}return r}}function S(t,e){return!t&&!e||Boolean(t)===Boolean(e)&&i.isDateSpansEqual(t.dateSpan,e.dateSpan)}function f(t,e){let i={};for(let n of e.pluginHooks.datePointTransforms)Object.assign(i,n(t,e));var n,s;return Object.assign(i,(n=t,{date:(s=e.dateEnv).toDate(n.range.start),dateStr:s.formatIso(n.range.start,{omitTime:n.allDay}),allDay:n.allDay})),i}class D extends i.Interaction{constructor(t){super(t),this.handlePointerDown=t=>{let{dragging:e}=this,i=t.origEvent.target;e.setIgnoreMove(!this.component.isValidDateDownEl(i))},this.handleDragEnd=t=>{let{component:e}=this,{pointer:i}=this.dragging;if(!i.wasTouchScroll){let{initialHit:i,finalHit:n}=this.hitDragging;if(i&&n&&S(i,n)){let{context:n}=e,s=Object.assign(Object.assign({},f(i.dateSpan,n)),{dayEl:i.dayEl,jsEvent:t.origEvent,view:n.viewApi||n.calendarApi.view});n.emitter.trigger("dateClick",s)}}},this.dragging=new p(t.el),this.dragging.autoScroller.isEnabled=!1;let e=this.hitDragging=new m(this.dragging,i.interactionSettingsToStore(t));e.emitter.on("pointerdown",this.handlePointerDown),e.emitter.on("dragend",this.handleDragEnd)}destroy(){this.dragging.destroy()}}class y extends i.Interaction{constructor(t){super(t),this.dragSelection=null,this.handlePointerDown=t=>{let{component:e,dragging:i}=this,{options:n}=e.context,s=n.selectable&&e.isValidDateDownEl(t.origEvent.target);i.setIgnoreMove(!s),i.delay=t.isTouch?function(t){let{options:e}=t.context,i=e.selectLongPressDelay;null==i&&(i=e.longPressDelay);return i}(e):null},this.handleDragStart=t=>{this.component.context.calendarApi.unselect(t)},this.handleHitUpdate=(t,e)=>{let{context:n}=this.component,s=null,r=!1;if(t){let e=this.hitDragging.initialHit;t.componentId===e.componentId&&this.isHitComboAllowed&&!this.isHitComboAllowed(e,t)||(s=function(t,e,n){let s=t.dateSpan,r=e.dateSpan,o=[s.range.start,s.range.end,r.range.start,r.range.end];o.sort(i.compareNumbers);let l={};for(let i of n){let n=i(t,e);if(!1===n)return null;n&&Object.assign(l,n)}return l.range={start:o[0],end:o[3]},l.allDay=s.allDay,l}(e,t,n.pluginHooks.dateSelectionTransformers)),s&&i.isDateSelectionValid(s,t.dateProfile,n)||(r=!0,s=null)}s?n.dispatch({type:"SELECT_DATES",selection:s}):e||n.dispatch({type:"UNSELECT_DATES"}),r?i.disableCursor():i.enableCursor(),e||(this.dragSelection=s)},this.handlePointerUp=t=>{this.dragSelection&&(i.triggerDateSelect(this.dragSelection,t,this.component.context),this.dragSelection=null)};let{component:e}=t,{options:n}=e.context,s=this.dragging=new p(t.el);s.touchScrollAllowed=!1,s.minDistance=n.selectMinDistance||0,s.autoScroller.isEnabled=n.dragScroll;let r=this.hitDragging=new m(this.dragging,i.interactionSettingsToStore(t));r.emitter.on("pointerdown",this.handlePointerDown),r.emitter.on("dragstart",this.handleDragStart),r.emitter.on("hitupdate",this.handleHitUpdate),r.emitter.on("pointerup",this.handlePointerUp)}destroy(){this.dragging.destroy()}}class w extends i.Interaction{constructor(t){super(t),this.subjectEl=null,this.subjectSeg=null,this.isDragging=!1,this.eventRange=null,this.relevantEvents=null,this.receivingContext=null,this.validMutation=null,this.mutatedRelevantEvents=null,this.handlePointerDown=t=>{let e=t.origEvent.target,{component:n,dragging:s}=this,{mirror:r}=s,{options:o}=n.context,l=n.context;this.subjectEl=t.subjectEl;let a=this.subjectSeg=i.getElSeg(t.subjectEl),h=(this.eventRange=a.eventRange).instance.instanceId;this.relevantEvents=i.getRelevantEvents(l.getCurrentData().eventStore,h),s.minDistance=t.isTouch?0:o.eventDragMinDistance,s.delay=t.isTouch&&h!==n.props.eventSelection?function(t){let{options:e}=t.context,i=e.eventLongPressDelay;null==i&&(i=e.longPressDelay);return i}(n):null,o.fixedMirrorParent?r.parentNode=o.fixedMirrorParent:r.parentNode=i.elementClosest(e,".fc"),r.revertDuration=o.dragRevertDuration;let c=n.isValidSegDownEl(e)&&!i.elementClosest(e,".fc-event-resizer");s.setIgnoreMove(!c),this.isDragging=c&&t.subjectEl.classList.contains("fc-event-draggable")},this.handleDragStart=t=>{let e=this.component.context,n=this.eventRange,s=n.instance.instanceId;t.isTouch?s!==this.component.props.eventSelection&&e.dispatch({type:"SELECT_EVENT",eventInstanceId:s}):e.dispatch({type:"UNSELECT_EVENT"}),this.isDragging&&(e.calendarApi.unselect(t),e.emitter.trigger("eventDragStart",{el:this.subjectEl,event:new i.EventImpl(e,n.def,n.instance),jsEvent:t.origEvent,view:e.viewApi}))},this.handleHitUpdate=(t,e)=>{if(!this.isDragging)return;let n=this.relevantEvents,s=this.hitDragging.initialHit,r=this.component.context,o=null,l=null,a=null,h=!1,c={affectedEvents:n,mutatedEvents:i.createEmptyEventStore(),isEvent:!0};if(t){o=t.context;let e=o.options;r===o||e.editable&&e.droppable?(l=function(t,e,n){let s=t.dateSpan,r=e.dateSpan,o=s.range.start,l=r.range.start,a={};s.allDay!==r.allDay&&(a.allDay=r.allDay,a.hasEnd=e.context.options.allDayMaintainDuration,r.allDay&&(o=i.startOfDay(o)));let h=i.diffDates(o,l,t.context.dateEnv,t.componentId===e.componentId?t.largeUnit:null);h.milliseconds&&(a.allDay=!1);let c={datesDelta:h,standardProps:a};for(let i of n)i(c,t,e);return c}(s,t,o.getCurrentData().pluginHooks.eventDragMutationMassagers),l&&(a=i.applyMutationToEventStore(n,o.getCurrentData().eventUiBases,l,o),c.mutatedEvents=a,i.isInteractionValid(c,t.dateProfile,o)||(h=!0,l=null,a=null,c.mutatedEvents=i.createEmptyEventStore()))):o=null}this.displayDrag(o,c),h?i.disableCursor():i.enableCursor(),e||(r===o&&S(s,t)&&(l=null),this.dragging.setMirrorNeedsRevert(!l),this.dragging.setMirrorIsVisible(!t||!this.subjectEl.getRootNode().querySelector(".fc-event-mirror")),this.receivingContext=o,this.validMutation=l,this.mutatedRelevantEvents=a)},this.handlePointerUp=()=>{this.isDragging||this.cleanup()},this.handleDragEnd=t=>{if(this.isDragging){let e=this.component.context,n=e.viewApi,{receivingContext:s,validMutation:r}=this,o=this.eventRange.def,l=this.eventRange.instance,a=new i.EventImpl(e,o,l),h=this.relevantEvents,c=this.mutatedRelevantEvents,{finalHit:d}=this.hitDragging;if(this.clearDrag(),e.emitter.trigger("eventDragStop",{el:this.subjectEl,event:a,jsEvent:t.origEvent,view:n}),r){if(s===e){let s=new i.EventImpl(e,c.defs[o.defId],l?c.instances[l.instanceId]:null);e.dispatch({type:"MERGE_EVENTS",eventStore:c});let d={oldEvent:a,event:s,relatedEvents:i.buildEventApis(c,e,l),revert(){e.dispatch({type:"MERGE_EVENTS",eventStore:h})}},g={};for(let t of e.getCurrentData().pluginHooks.eventDropTransformers)Object.assign(g,t(r,e));e.emitter.trigger("eventDrop",Object.assign(Object.assign(Object.assign({},d),g),{el:t.subjectEl,delta:r.datesDelta,jsEvent:t.origEvent,view:n})),e.emitter.trigger("eventChange",d)}else if(s){let r={event:a,relatedEvents:i.buildEventApis(h,e,l),revert(){e.dispatch({type:"MERGE_EVENTS",eventStore:h})}};e.emitter.trigger("eventLeave",Object.assign(Object.assign({},r),{draggedEl:t.subjectEl,view:n})),e.dispatch({type:"REMOVE_EVENTS",eventStore:h}),e.emitter.trigger("eventRemove",r);let g=c.defs[o.defId],u=c.instances[l.instanceId],p=new i.EventImpl(s,g,u);s.dispatch({type:"MERGE_EVENTS",eventStore:c});let v={event:p,relatedEvents:i.buildEventApis(c,s,u),revert(){s.dispatch({type:"REMOVE_EVENTS",eventStore:c})}};s.emitter.trigger("eventAdd",v),t.isTouch&&s.dispatch({type:"SELECT_EVENT",eventInstanceId:l.instanceId}),s.emitter.trigger("drop",Object.assign(Object.assign({},f(d.dateSpan,s)),{draggedEl:t.subjectEl,jsEvent:t.origEvent,view:d.context.viewApi})),s.emitter.trigger("eventReceive",Object.assign(Object.assign({},v),{draggedEl:t.subjectEl,view:d.context.viewApi}))}}else e.emitter.trigger("_noEventDrop")}this.cleanup()};let{component:e}=this,{options:n}=e.context,s=this.dragging=new p(t.el);s.pointer.selector=w.SELECTOR,s.touchScrollAllowed=!1,s.autoScroller.isEnabled=n.dragScroll;let r=this.hitDragging=new m(this.dragging,i.interactionSettingsStore);r.useSubjectCenter=t.useEventCenter,r.emitter.on("pointerdown",this.handlePointerDown),r.emitter.on("dragstart",this.handleDragStart),r.emitter.on("hitupdate",this.handleHitUpdate),r.emitter.on("pointerup",this.handlePointerUp),r.emitter.on("dragend",this.handleDragEnd)}destroy(){this.dragging.destroy()}displayDrag(t,e){let n=this.component.context,s=this.receivingContext;s&&s!==t&&(s===n?s.dispatch({type:"SET_EVENT_DRAG",state:{affectedEvents:e.affectedEvents,mutatedEvents:i.createEmptyEventStore(),isEvent:!0}}):s.dispatch({type:"UNSET_EVENT_DRAG"})),t&&t.dispatch({type:"SET_EVENT_DRAG",state:e})}clearDrag(){let t=this.component.context,{receivingContext:e}=this;e&&e.dispatch({type:"UNSET_EVENT_DRAG"}),t!==e&&t.dispatch({type:"UNSET_EVENT_DRAG"})}cleanup(){this.subjectSeg=null,this.isDragging=!1,this.eventRange=null,this.relevantEvents=null,this.receivingContext=null,this.validMutation=null,this.mutatedRelevantEvents=null}}w.SELECTOR=".fc-event-draggable, .fc-event-resizable";class T extends i.Interaction{constructor(t){super(t),this.draggingSegEl=null,this.draggingSeg=null,this.eventRange=null,this.relevantEvents=null,this.validMutation=null,this.mutatedRelevantEvents=null,this.handlePointerDown=t=>{let{component:e}=this,n=this.querySegEl(t),s=i.getElSeg(n),r=this.eventRange=s.eventRange;this.dragging.minDistance=e.context.options.eventDragMinDistance,this.dragging.setIgnoreMove(!this.component.isValidSegDownEl(t.origEvent.target)||t.isTouch&&this.component.props.eventSelection!==r.instance.instanceId)},this.handleDragStart=t=>{let{context:e}=this.component,n=this.eventRange;this.relevantEvents=i.getRelevantEvents(e.getCurrentData().eventStore,this.eventRange.instance.instanceId);let s=this.querySegEl(t);this.draggingSegEl=s,this.draggingSeg=i.getElSeg(s),e.calendarApi.unselect(),e.emitter.trigger("eventResizeStart",{el:s,event:new i.EventImpl(e,n.def,n.instance),jsEvent:t.origEvent,view:e.viewApi})},this.handleHitUpdate=(t,e,n)=>{let{context:s}=this.component,r=this.relevantEvents,o=this.hitDragging.initialHit,l=this.eventRange.instance,a=null,h=null,c=!1,d={affectedEvents:r,mutatedEvents:i.createEmptyEventStore(),isEvent:!0};if(t){t.componentId===o.componentId&&this.isHitComboAllowed&&!this.isHitComboAllowed(o,t)||(a=function(t,e,n,s){let r=t.context.dateEnv,o=t.dateSpan.range.start,l=e.dateSpan.range.start,a=i.diffDates(o,l,r,t.largeUnit);if(n){if(r.add(s.start,a)<s.end)return{startDelta:a}}else if(r.add(s.end,a)>s.start)return{endDelta:a};return null}(o,t,n.subjectEl.classList.contains("fc-event-resizer-start"),l.range))}a&&(h=i.applyMutationToEventStore(r,s.getCurrentData().eventUiBases,a,s),d.mutatedEvents=h,i.isInteractionValid(d,t.dateProfile,s)||(c=!0,a=null,h=null,d.mutatedEvents=null)),h?s.dispatch({type:"SET_EVENT_RESIZE",state:d}):s.dispatch({type:"UNSET_EVENT_RESIZE"}),c?i.disableCursor():i.enableCursor(),e||(a&&S(o,t)&&(a=null),this.validMutation=a,this.mutatedRelevantEvents=h)},this.handleDragEnd=t=>{let{context:e}=this.component,n=this.eventRange.def,s=this.eventRange.instance,r=new i.EventImpl(e,n,s),o=this.relevantEvents,l=this.mutatedRelevantEvents;if(e.emitter.trigger("eventResizeStop",{el:this.draggingSegEl,event:r,jsEvent:t.origEvent,view:e.viewApi}),this.validMutation){let a=new i.EventImpl(e,l.defs[n.defId],s?l.instances[s.instanceId]:null);e.dispatch({type:"MERGE_EVENTS",eventStore:l});let h={oldEvent:r,event:a,relatedEvents:i.buildEventApis(l,e,s),revert(){e.dispatch({type:"MERGE_EVENTS",eventStore:o})}};e.emitter.trigger("eventResize",Object.assign(Object.assign({},h),{el:this.draggingSegEl,startDelta:this.validMutation.startDelta||i.createDuration(0),endDelta:this.validMutation.endDelta||i.createDuration(0),jsEvent:t.origEvent,view:e.viewApi})),e.emitter.trigger("eventChange",h)}else e.emitter.trigger("_noEventResize");this.draggingSeg=null,this.relevantEvents=null,this.validMutation=null};let{component:e}=t,n=this.dragging=new p(t.el);n.pointer.selector=".fc-event-resizer",n.touchScrollAllowed=!1,n.autoScroller.isEnabled=e.context.options.dragScroll;let s=this.hitDragging=new m(this.dragging,i.interactionSettingsToStore(t));s.emitter.on("pointerdown",this.handlePointerDown),s.emitter.on("dragstart",this.handleDragStart),s.emitter.on("hitupdate",this.handleHitUpdate),s.emitter.on("dragend",this.handleDragEnd)}destroy(){this.dragging.destroy()}querySegEl(t){return i.elementClosest(t.subjectEl,".fc-event")}}const M={fixedMirrorParent:i.identity},b={dateClick:i.identity,eventDragStart:i.identity,eventDragStop:i.identity,eventDrop:i.identity,eventResizeStart:i.identity,eventResizeStop:i.identity,eventResize:i.identity,drop:i.identity,eventReceive:i.identity,eventLeave:i.identity};class C{constructor(t,e){this.receivingContext=null,this.droppableEvent=null,this.suppliedDragMeta=null,this.dragMeta=null,this.handleDragStart=t=>{this.dragMeta=this.buildDragMeta(t.subjectEl)},this.handleHitUpdate=(t,e,n)=>{let{dragging:s}=this.hitDragging,r=null,o=null,l=!1,a={affectedEvents:i.createEmptyEventStore(),mutatedEvents:i.createEmptyEventStore(),isEvent:this.dragMeta.create};t&&(r=t.context,this.canDropElOnCalendar(n.subjectEl,r)&&(o=function(t,e,n){let s=Object.assign({},e.leftoverProps);for(let i of n.pluginHooks.externalDefTransforms)Object.assign(s,i(t,e));let{refined:r,extra:o}=i.refineEventDef(s,n),l=i.parseEventDef(r,o,e.sourceId,t.allDay,n.options.forceEventDuration||Boolean(e.duration),n),a=t.range.start;t.allDay&&e.startTime&&(a=n.dateEnv.add(a,e.startTime));let h=e.duration?n.dateEnv.add(a,e.duration):i.getDefaultEventEnd(t.allDay,a,n),c=i.createEventInstance(l.defId,{start:a,end:h});return{def:l,instance:c}}(t.dateSpan,this.dragMeta,r),a.mutatedEvents=i.eventTupleToStore(o),l=!i.isInteractionValid(a,t.dateProfile,r),l&&(a.mutatedEvents=i.createEmptyEventStore(),o=null))),this.displayDrag(r,a),s.setMirrorIsVisible(e||!o||!document.querySelector(".fc-event-mirror")),l?i.disableCursor():i.enableCursor(),e||(s.setMirrorNeedsRevert(!o),this.receivingContext=r,this.droppableEvent=o)},this.handleDragEnd=t=>{let{receivingContext:e,droppableEvent:n}=this;if(this.clearDrag(),e&&n){let s=this.hitDragging.finalHit,r=s.context.viewApi,o=this.dragMeta;if(e.emitter.trigger("drop",Object.assign(Object.assign({},f(s.dateSpan,e)),{draggedEl:t.subjectEl,jsEvent:t.origEvent,view:r})),o.create){let s=i.eventTupleToStore(n);e.dispatch({type:"MERGE_EVENTS",eventStore:s}),t.isTouch&&e.dispatch({type:"SELECT_EVENT",eventInstanceId:n.instance.instanceId}),e.emitter.trigger("eventReceive",{event:new i.EventImpl(e,n.def,n.instance),relatedEvents:[],revert(){e.dispatch({type:"REMOVE_EVENTS",eventStore:s})},draggedEl:t.subjectEl,view:r})}}this.receivingContext=null,this.droppableEvent=null};let n=this.hitDragging=new m(t,i.interactionSettingsStore);n.requireInitial=!1,n.emitter.on("dragstart",this.handleDragStart),n.emitter.on("hitupdate",this.handleHitUpdate),n.emitter.on("dragend",this.handleDragEnd),this.suppliedDragMeta=e}buildDragMeta(t){return"object"==typeof this.suppliedDragMeta?i.parseDragMeta(this.suppliedDragMeta):"function"==typeof this.suppliedDragMeta?i.parseDragMeta(this.suppliedDragMeta(t)):function(t){let e=function(t,e){let n=i.config.dataAttrPrefix,s=(n?n+"-":"")+e;return t.getAttribute("data-"+s)||""}(t,"event"),n=e?JSON.parse(e):{create:!1};return i.parseDragMeta(n)}(t)}displayDrag(t,e){let i=this.receivingContext;i&&i!==t&&i.dispatch({type:"UNSET_EVENT_DRAG"}),t&&t.dispatch({type:"SET_EVENT_DRAG",state:e})}clearDrag(){this.receivingContext&&this.receivingContext.dispatch({type:"UNSET_EVENT_DRAG"})}canDropElOnCalendar(t,e){let n=e.options.dropAccept;return"function"==typeof n?n.call(e.calendarApi,t):"string"!=typeof n||!n||Boolean(i.elementMatches(t,n))}}i.config.dataAttrPrefix="";class R extends i.ElementDragging{constructor(t){super(t),this.shouldIgnoreMove=!1,this.mirrorSelector="",this.currentMirrorEl=null,this.handlePointerDown=t=>{this.emitter.trigger("pointerdown",t),this.shouldIgnoreMove||this.emitter.trigger("dragstart",t)},this.handlePointerMove=t=>{this.shouldIgnoreMove||this.emitter.trigger("dragmove",t)},this.handlePointerUp=t=>{this.emitter.trigger("pointerup",t),this.shouldIgnoreMove||this.emitter.trigger("dragend",t)};let e=this.pointer=new o(t);e.emitter.on("pointerdown",this.handlePointerDown),e.emitter.on("pointermove",this.handlePointerMove),e.emitter.on("pointerup",this.handlePointerUp)}destroy(){this.pointer.destroy()}setIgnoreMove(t){this.shouldIgnoreMove=t}setMirrorIsVisible(t){if(t)this.currentMirrorEl&&(this.currentMirrorEl.style.visibility="",this.currentMirrorEl=null);else{let t=this.mirrorSelector?document.querySelector(this.mirrorSelector):null;t&&(this.currentMirrorEl=t,t.style.visibility="hidden")}}}var I=e.createPlugin({name:"@fullcalendar/interaction",componentInteractions:[D,y,w,T],calendarInteractions:[class{constructor(t){this.context=t,this.isRecentPointerDateSelect=!1,this.matchesCancel=!1,this.matchesEvent=!1,this.onSelect=t=>{t.jsEvent&&(this.isRecentPointerDateSelect=!0)},this.onDocumentPointerDown=t=>{let e=this.context.options.unselectCancel,n=i.getEventTargetViaRoot(t.origEvent);this.matchesCancel=!!i.elementClosest(n,e),this.matchesEvent=!!i.elementClosest(n,w.SELECTOR)},this.onDocumentPointerUp=t=>{let{context:e}=this,{documentPointer:i}=this,n=e.getCurrentData();if(!i.wasTouchScroll){if(n.dateSelection&&!this.isRecentPointerDateSelect){let i=e.options.unselectAuto;!i||i&&this.matchesCancel||e.calendarApi.unselect(t)}n.eventSelection&&!this.matchesEvent&&e.dispatch({type:"UNSELECT_EVENT"})}this.isRecentPointerDateSelect=!1};let e=this.documentPointer=new o(document);e.shouldIgnoreMove=!0,e.shouldWatchScroll=!1,e.emitter.on("pointerdown",this.onDocumentPointerDown),e.emitter.on("pointerup",this.onDocumentPointerUp),t.emitter.on("select",this.onSelect)}destroy(){this.context.emitter.off("select",this.onSelect),this.documentPointer.destroy()}}],elementDraggingImpl:p,optionRefiners:M,listenerRefiners:b});return e.globalPlugins.push(I),t.Draggable=class{constructor(t,e={}){this.handlePointerDown=t=>{let{dragging:e}=this,{minDistance:n,longPressDelay:s}=this.settings;e.minDistance=null!=n?n:t.isTouch?0:i.BASE_OPTION_DEFAULTS.eventDragMinDistance,e.delay=t.isTouch?null!=s?s:i.BASE_OPTION_DEFAULTS.longPressDelay:0},this.handleDragStart=t=>{t.isTouch&&this.dragging.delay&&t.subjectEl.classList.contains("fc-event")&&this.dragging.mirror.getMirrorEl().classList.add("fc-event-selected")},this.settings=e;let n=this.dragging=new p(t);n.touchScrollAllowed=!1,null!=e.itemSelector&&(n.pointer.selector=e.itemSelector),null!=e.appendTo&&(n.mirror.parentNode=e.appendTo),n.emitter.on("pointerdown",this.handlePointerDown),n.emitter.on("dragstart",this.handleDragStart),new C(n,e.eventData)}destroy(){this.dragging.destroy()}},t.ThirdPartyDraggable=class{constructor(t,e){let i=document;t===document||t instanceof Element?(i=t,e=e||{}):e=t||{};let n=this.dragging=new R(i);"string"==typeof e.itemSelector?n.pointer.selector=e.itemSelector:i===document&&(n.pointer.selector="[data-event]"),"string"==typeof e.mirrorSelector&&(n.mirrorSelector=e.mirrorSelector),new C(n,e.eventData)}destroy(){this.dragging.destroy()}},t.default=I,Object.defineProperty(t,"__esModule",{value:!0}),t}({},FullCalendar,FullCalendar.Internal);