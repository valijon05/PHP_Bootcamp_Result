import{p as c,R as E,d as u}from"./Cuqdu9nB.js";import{E as n}from"./BefdN0MI.js";const b=o=>{var s,i,r,t,p;let e={php_version:(s=o.payload.meta)==null?void 0:s.php_version,laravel_version:"",symfony_version:""};(i=o.payload.meta)!=null&&i.laravel_version?e.laravel_version=(r=o.payload.meta)==null?void 0:r.laravel_version:(t=o.payload.meta)!=null&&t.symfony_version&&(e.symfony_version=(p=o.payload.meta)==null?void 0:p.symfony_version),o.payload.payloads.forEach(a=>{a.origin&&(e={...e,...c(a.origin,["file","line_number","hostname"])})});const y=o.payload.payloads.filter(a=>a.type==="label").map(a=>{var l;return(l=a==null?void 0:a.content)==null?void 0:l.label}).filter(Boolean),m=o.payload.payloads.filter(a=>Object.values(E).includes(a.type)).map(a=>a.type).filter(Boolean),f=o.payload.payloads.filter(a=>a.type==="color").map(a=>{var l;return(l=a.content)==null?void 0:l.color}).filter(Boolean).shift()||"black",d=o.payload.payloads.filter(a=>a.type==="size").map(a=>{var l;return(l=a.content)==null?void 0:l.size}).filter(Boolean).shift()||"md";return{id:o.uuid,type:n.RAY_DUMP,labels:[n.RAY_DUMP,...y,...m].filter((a,l,_)=>_.indexOf(a)===l),origin:e,serverName:"",date:o.timestamp?new Date(o.timestamp*1e3):null,payload:o.payload,meta:{color:f,size:d}}},R=()=>({normalizeRayEvent:b,COMPONENT_TYPE_MAP:u});export{R as u};