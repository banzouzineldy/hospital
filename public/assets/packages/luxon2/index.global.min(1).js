/*!
FullCalendar Luxon 2 Plugin v6.1.5
Docs & License: https://fullcalendar.io/docs/luxon2
(c) 2023 Adam Shaw
*/
FullCalendar.Luxon2=function(e,t,n,a){"use strict";function l(e,t,a){return n.DateTime.fromObject({year:e[0],month:e[1]+1,day:e[2],hour:e[3],minute:e[4],second:e[5],millisecond:e[6]},{locale:a,zone:t})}class r extends a.NamedTimeZoneImpl{offsetForArray(e){return l(e,this.timeZoneName).offset}timestampToArray(e){return[(t=n.DateTime.fromMillis(e,{zone:this.timeZoneName})).year,t.month-1,t.day,t.hour,t.minute,t.second,t.millisecond];var t}}var o=t.createPlugin({name:"@fullcalendar/luxon2",cmdFormatter:function(e,t){let n=function e(t){let n=t.match(/^(.*?)\{(.*)\}(.*)$/);if(n){let t=e(n[2]);return{head:n[1],middle:t,tail:n[3],whole:n[1]+t.whole+n[3]}}return{head:null,middle:null,tail:null,whole:t}}(e);if(t.end){let e=l(t.start.array,t.timeZone,t.localeCodes[0]),a=l(t.end.array,t.timeZone,t.localeCodes[0]);return function e(t,n,a,l){if(t.middle){let r=n(t.head),o=e(t.middle,n,a,l),i=n(t.tail),u=a(t.head),d=e(t.middle,n,a,l),m=a(t.tail);if(r===u&&i===m)return r+(o===d?o:o+l+d)+i}let r=n(t.whole),o=a(t.whole);if(r===o)return r;return r+l+o}(n,e.toFormat.bind(e),a.toFormat.bind(a),t.defaultSeparator)}return l(t.date.array,t.timeZone,t.localeCodes[0]).toFormat(n.whole)},namedTimeZonedImpl:r});return t.globalPlugins.push(o),e.default=o,e.toLuxonDateTime=function(e,t){if(!(t instanceof a.CalendarImpl))throw new Error("must supply a CalendarApi instance");let{dateEnv:l}=t.getCurrentData();return n.DateTime.fromJSDate(e,{zone:l.timeZone,locale:l.locale.codes[0]})},e.toLuxonDuration=function(e,t){if(!(t instanceof a.CalendarImpl))throw new Error("must supply a CalendarApi instance");let{dateEnv:l}=t.getCurrentData();return n.Duration.fromObject(e,{locale:l.locale.codes[0]})},Object.defineProperty(e,"__esModule",{value:!0}),e}({},FullCalendar,luxon,FullCalendar.Internal);