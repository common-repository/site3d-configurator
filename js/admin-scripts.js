jQuery(document).ready(function ($)
{
  "use strict";
  "esversion: 6";
  $(".site3d__button--result").click(function (ev)
  {
    var idW = $('input.site3d__input--text').val();
    var idContainerW = $('input.site3d__input--text');
    var widthW = $('input[id="widthW"]').val();
    var heightW = $('input.site3d__input--range').val();
    if (idW === "")
    {
      idContainerW.addClass("site3d__input--error");
      return false;
    }
    
    if (idW > 0 && idW !== undefined)
    {
      $.ajax({
        url: Site3DAdminJs_obj.ajaxurl,
        data: {
          action: "site3d_ajax_convert",
          security: Site3DAdminJs_obj.nonce,
          idW: idW,
          widthW: widthW,
          heightW: heightW,
        },
        method: "POST",
        success: function (response)
        {
          var data = JSON.parse(response);
          console.log("heli2");
          if (data.status === true)
          {
            if (navigator.clipboard !== undefined)
            {
              navigator.clipboard
                       .writeText(data.answer)
                       .then(() =>
                       {
                       })
                       .catch((err) =>
                       {
                         console.log("Something went wrong", err);
                       });
            }
            
            $(".site3d__shirt-code").html(
              '<textarea rows="2" class="site3d__result" name="shirt-code">' +
              data.answer +
              "</textarea>"
            );
            $(".site3d__copied").fadeIn();
            idContainerW.removeClass("site3d__input--error");
          }
        },
        error: function (error)
        {
          alert(error);
        },
      });
      ev.preventDefault();
    }
  });
  
  let range = document.querySelector("input.site3d__input--range");
  range.addEventListener("input", getValue);
  
  let output = document.querySelector(".site3d__range-output");
  output.value = range.value + "px";
  
  function getValue()
  {
    output.value = range.value + "px";
  }
  
  let buttons = document.querySelectorAll(".site3d__button");
  
  for (let btn of buttons)
  {
    btn.addEventListener("click", activeBtn);
  }
  
  function activeBtn(e)
  {
    for (let btn of buttons)
    {
      if (btn.classList.contains("site3d__button--active"))
      {
        btn.classList.remove("site3d__button--active");
      }
    }
    if (this.classList.contains("site3d__button--open-range"))
    {
      range.style.display = "block";
      output.style.display = "block";
    }
    else
    {
      range.style.display = "none";
      output.style.display = "none";
    }
    this.classList.add("site3d__button--active");
    range.value = this.value;
    output.value = this.value + "px";
  }
});