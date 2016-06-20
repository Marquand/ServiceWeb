function dolink(ad){
   link = 'mailto:' + ad.replace(/\.\..+t\.\./,"@"); 
   return link;
}

/* <a href="#" onclick="location.href=dolink(this.title); return false;" title="contact..åt..nomdedomaine.com">Email</a> */