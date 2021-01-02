const { forEach } = require("lodash");

//QuicView JS
jQuery(document).ready(function ($) {
  /**
  option1:x
  option2:white
  **/
  var currentVariants = {};

  /**
  0 = option1:x, option2: white
  1 = option1:x, option2: black
  2 = option1:xxl, option2: white
  **/
  var availableVariants = [];

  /**
  0 = true
  1 = false
  2 = true
  **/
  var variantAvailability = [];
  var multipleSelection = false;

  $(document).on('click','.quick-view',function () {
    currentVariants = {};
    availableVariants = [];
    variantAvailability = [];
    variantPrices = [];
    variantCompareAtPrices = [];
    var product = $(this).parents(".grid__item,.list__item");
    var productName = product.find('.grid-view-item__title').text();
    var productDescription = product.find('.product-desc').html();
    var productPrice = product.find('.grid-view-item__meta').html();
    var productRating = product.find('.spr-badge').html();
    var productDiscount = product.find('.discount-percentage').html();
    var productQuantity = product.find('.qv-cartmain').html();

    $('#main-thumbnail').attr('src',product.find('.imgurl-for-quickview span').first().text());
      product.find('.imgurl-for-quickview span').each(function(index){
        var html = "<div class='thumb-item'><img src='"+$(this).text()+"'></div>";
        qvthumbnails.trigger('add.owl.carousel', html).trigger('refresh.owl.carousel');
      });
    $('#qv-productname').html(productName);
    $('#qv-productprice').html(productPrice);
    $('#qv-spr-badge').html(productRating);
    $('#qv-productdescription').html(productDescription);
    $('#qv-discount').html(productDiscount);
    $('#qv-quantity').html(productQuantity);

    multipleSelection = $('#qv-quantity .selector-wrapper').length > 0;
    if(multipleSelection) {
      loadAvailableQuickViewVariants();
      loadCurrentQuickViewVariants();
    }
    $('.qv-addToCart span.adding').hide();
    $('#quickviewModal').modal('show');
  });

  $(document).on( "click","#qv-thumbnails img",function() {
    $('#main-thumbnail').attr('src',$(this).attr('src'));
  });

  $(document).on( "change","#qv-quantity select.single-option-selector",function() {
    if(multipleSelection) {
      currentVariants[$(this).data('index')] = $(this).val();
      checkAvailability();
    }
  });

  $(document).on( "click","#qv-quantity .qv-addToCart.enable",function() {
    if($(".cart-display #cart-container").hasClass('in')){
      $(".cart-display #cart-container").removeClass('in');
      $(".cart-display .cart-title").addClass('collapsed');
      $(".cart-display .cart-title").attr("aria-expanded", "false");
    }
    qvAddToCart();
  });

  // product page addtocart
  $(document).on( "click",".cart-product-page .product-addToCart",function() {
      var variantID = $('.product-selection .cart-product-page select[name="id"] option:selected').val();
      var qty =  $('.product-selection .cart-product-page input[name="quantity"]').val();
      productAddToCart(variantID, qty);
      if($(".cart-display #cart-container").hasClass('in')){
        $(".cart-display #cart-container").removeClass('in');
        $(".cart-display .cart-title").addClass('collapsed');
        $(".cart-display .cart-title").attr("aria-expanded", "false");
      }
  });

    $(document).on("click",".ishi-product-swatch .ishi-custom-swatch .custom-swatch",function() {
    $(this).parent().find('.custom-swatch').removeClass("active");
    $(this).addClass("active");
    var selectorID = $(this).parent().data("selector");
    var index = $(this).data("index");
    $('#' + selectorID + ' option').eq(index).prop('selected', true).trigger('change');
  });


  $(document).on("click",".ishi-quickview-swatch .ishi-custom-swatch .custom-swatch",function() {
    $(this).parent().find('.custom-swatch').removeClass("active");
    $(this).addClass("active");
    var selectorID = $(this).parent().data("selector");
    var index = $(this).data("index");
    $('#qv-quantity select[data-index="' + selectorID + '"] option').eq(index).prop('selected', true).trigger('change');
  });

  function loadCurrentQuickViewVariants() {
    currentVariants = {};
    $('#qv-quantity .selector-wrapper').each(function(index){
        currentVariants["option" + (index+1)] = $(this).find('select.single-option-selector').val();
    });
    checkAvailability();
  }

  function loadAvailableQuickViewVariants() {
    availableVariants = [];
    $('#qv-quantity select[name="id"] > option').each(function(index){
        var variantsList = $(this).text().split("/");
        var variantsKeyVal = {};
        for(var i=0; i < variantsList.length; i++) {
          variantsKeyVal["option" + (i+1)] = variantsList[i].trim();
        }
        availableVariants.push(variantsKeyVal);
        variantAvailability.push($(this).data('available'));
        variantPrices.push($(this).data('price'));
        variantCompareAtPrices.push($(this).data('compareatprice'));
    });
  }

  function checkAvailability() {
    var available = false;
    var backupIndex=0;
    for(i=0; i < availableVariants.length;) {
        if(matchArray(availableVariants[i],currentVariants)) {
           backupIndex = i;
        }
        if(matchArray(availableVariants[i],currentVariants) && variantAvailability[i]) {
          available = true;
          break;
        }
        i++;
    }
    $('#qv-quantity select[name="id"] option').each(function(index){
         $(this).removeAttr('selected', 'selected');
    });
    $('#qv-quantity select[name="id"] option').eq(backupIndex).prop("selected", "selected");

    $('#qv-productprice span.qv-regularprice').text($('#qv-quantity select[name="id"] option').eq(backupIndex).data("price"));
    $('#qv-productprice span.qv-discountprice').text($('#qv-quantity select[name="id"] option').eq(backupIndex).data("compareatprice"));


    if(available) {
      $('#quickviewModal .qv-addToCart').addClass('enable');
      $('#quickviewModal .qv-addToCart span.instock').show();
      $('#quickviewModal .qv-addToCart span.outstock').hide();
    } else {
      $('#quickviewModal .qv-addToCart.enable').removeClass('enable');
      $('#quickviewModal .qv-addToCart span.instock').hide();
      $('#quickviewModal .qv-addToCart span.outstock').show();
    }
  }

  function matchArray(a, b) {
    var match = true;
    for (var key in a) {
      if(a[key] != b[key]) {
          match = false;
      }
    }
    return match;
  }
  function qvAddToCart() {
    var variantID = $('#qv-quantity select[name="id"] option:selected').val();
    var qty =  $('#qv-quantity input[name="quantity"]').val();
    console.log('llego a varoane');

      $('.qv-addToCart span.instock').hide();
      $('.qv-addToCart span.outstock').hide();
      $('.qv-addToCart span.adding').show();
      productAddToCart(variantID,qty);
  }

  $("#quickviewModal").on('hide.bs.modal', function () {
    var itemcount = $('#qv-thumbnails .owl-item').length;
    for(var i=0; i<itemcount; i++) {
      qvthumbnails.trigger('remove.owl.carousel', i);
    }
  });

});

function productAddToCart(varID,qty) {
  // console.log('datos del producto ->',varID,qty);
          $.ajax({
            url: "{{ url('/libros/pedido') }}",
            type:"POST",
            beforeSend: function (xhr) {
                var token = $('meta[name="csrf-token"]').attr('content');

                if (token) {
                      return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
           type:'POST',
           url:"/lacasadelderecho/public/libros/pedido",
           data: "quantity=" + qty + "&id=" + varID,
           dataType: "json",

           success:function(data){

              var url='e';

              var title =data.nombre;
              var discounted_price =data.precio;
              var quantity =data.quantity;
              var variant_id = 2;
              var image=data.imagen;
              var e=data.carritoDet;
              var totalcar=null;
               totalcar= parseFloat(data.total);
                  var imessage = $("#cartmessage .title-success").text();
                  var url = "<a href="+ url + ">" + title +"</a>";
                  imessage += url + " - "+ quantity + " x "+ Shopify.formatMoney(discounted_price, Shopify.money_format);
                  $.notify({message:imessage},{type:"success",offset:0,placement:{from:"top",align:"center"},z_index: 9999,animate:{enter:"animated fadeInDown",exit:"animated fadeOutUp"},template:'<div data-notify="container" class="col-xs-12 alert alert-{0}" role="alert"><button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button><span data-notify="icon"></span> <span data-notify="title">{1}</span> <span data-notify="message">{2}</span><div class="progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div></div><a href="{3}" target="{4}" data-notify="url"></a></div>'});
                  // adjustCartDropDown();
                  $('.qv-addToCart span.instock').show();
                  $('.qv-addToCart span.adding').hide();
                  $('.nm-addToCart').removeClass('adding');


                          var productList = $("#cart-container .product-list");
                            productList.html('');
                            var cartempty = $("#cart-container .cart__empty");
                            var cartfooter = $("#cart-container .cart__footer");
                            $('.cart__subtotal').html(Shopify.formatMoney(totalcar.toFixed(2), Shopify.money_format));
                            $('.cart-qty').html(e.length);
                            if(e.length > 0) {
                              for (y=0;y<e.length; y++) {
                                 var product =$("<div class='product'></div>");
                                  var productimg =$("<div class='product-img'></div>");
                                  var productdata =$("<div class='product-data'></div>");
                                   productimg.append("<img src='"+e[y].img_producto+"' alt='"+e[y].nombre_producto+"''>");
                                  productdata.append("<a href='" + url + "' class='product-title'>" +e[y].nombre_producto+ "</a>");
                                  productdata.append("<span class='product-price'>" + e[y].cantidad + " x "+ Shopify.formatMoney(e[y].precio, Shopify.money_format)+ "</span>");
                                  product.append(productimg);
                                  product.append(productdata);
                                  product.append("<a class='remove' data-variantid=" +e[y].item+ "><i class='material-icons'>delete</i></a>")
                                  productList.append(product);
                              }
                              cartfooter.removeClass('hide');
                              productList.removeClass('hide');
                              cartempty.addClass('hide');
                              productList.slimScroll({
                                height: e.length > 1 ? '262px' : '100%'
                              });
                            } else {
                                cartempty.removeClass('hide');
                                cartfooter.addClass('hide');
                                productList.addClass('hide');
                            }



           },
           error:function(){
              // alert("error!!!!");
        }

        });


}

