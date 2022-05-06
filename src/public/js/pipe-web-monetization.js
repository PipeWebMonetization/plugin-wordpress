jQuery(function ($) {
  var count = 0;
  var totalAmount = 0;
  
  if (!ajax_variables.logged_in) {
    $.ajax({
      url: ajax_variables.ajax_url,
      type: 'post',
      data: { random: Math.random() },
      success: function (response) {
        $("head").append(response);
        simulateMonetization()
        // document.head.appendChild(response)
      }
    })
  }
  if (document.monetization) {
    document.monetization.addEventListener('monetizationstart', () => {
      console.log("start")
      //   document.getElementById('exclusive').classList.remove('hidden')
    })
    document.monetization.addEventListener('monetizationprogress', event => {
      console.log("progress")
      count++;
      totalAmount += Number(event.detail.amount);
      console.log(Number(event.detail.amount))
    })

  }

  function simulateMonetization() {

    if (document.monetization) {
      const randomGuid = 'c7ff7da9-8a41-4660-98a8-ca4df0176fbe';

      const meta = document.querySelector('meta[name="monetization"]');
      let metaContent = null;
      if (meta) {
        metaContent = meta.getAttribute('content');
      }

      if (metaContent) {
        const resolvedEndpoint = metaContent.replace(/^\$/, 'https://');

        const monetizationstartEvent = new CustomEvent('monetizationstart', {
          detail: {
            requestId: randomGuid,
            id: randomGuid,
            metaContent,
            resolvedEndpoint
          }
        });


        const monetizationprogressEvent = new CustomEvent('monetizationprogress', {
          detail: {
            "amount": "200000",
            "assetCode": "USD",
            "assetScale": 9
          }
        });

        document.monetization.dispatchEvent(monetizationstartEvent);
        document.monetization.dispatchEvent(monetizationprogressEvent);

        setInterval(() => {
          document.monetization.dispatchEvent(monetizationprogressEvent);
        }, 2000)
      }
      else {
        alert('monetization meta tag is not correctly configured.')
      }
    }
  }

});
