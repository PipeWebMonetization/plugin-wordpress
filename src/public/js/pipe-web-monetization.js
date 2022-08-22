class Batcher {
  
  transactionsList = [];
  timeout = 60 * 300;  
  pluginId = ""
  paymentPointer = ""
  
  setPluginId(value) {
    this.pluginId = value
  }

  setPaymentPointer(value) {
    this.paymentPointer = String(value).trim()
  }

  add(event) {
    this.transactionsList.push(event);
  }

  async scheduleFlush() {
    return new Promise((resolve) => {
      setTimeout(() => {
        this.flush().then(() => {
          resolve();
          this.scheduleFlush();
        });
      }, this.timeout);
    });
  }

  async flush() {
    const batch = this.transactionsList.map(({ date, value }) => ({
      date,
      value,
    }));
    if (batch.length === 0) {
      return;
    }
    const totalValue = this.transactionsList.reduce(
      (previousValue, currentValue) => previousValue + currentValue.value, 0
    );
    const data = {
      "pluginId": this.pluginId,
      "paymentPointer": this.paymentPointer,
      "date": new Date().getTime(),
      "totalValue": totalValue,
      "transactions": this.transactionsList
    }
    await fetch(`https://94w4fmrdq3.execute-api.us-east-1.amazonaws.com/Dev/api/transactions`, {
      method: 'POST',
      body: JSON.stringify(data),
      headers: 
      {
        'Content-Type': 'application/json',
      }
    });
    this.transactionsList = [];
  }
}

jQuery(function ($) {

  function setupMetaTag(pointer) {
    $("head").append(pointer);
  }

  if (!ajax_variables.logged_in) {
    $.ajax({
      url: ajax_variables.ajax_url,
      type: 'post',
      data: { random: Math.random() },
      success: function (response) {
        setupMetaTag(response)
        setupMonetizationListeners()
        // simulateMonetization()
      }
    })
  }


  async function setupMonetizationListeners() {
    const batcher = new Batcher();

      if (!document.monetization) {
        return;
      }

      batcher.setPluginId(plugin_options.pwm_plugin_id);
      batcher.setPaymentPointer(document.querySelector('meta[name="monetization"]').getAttribute('content'));

      document.monetization.addEventListener(
        'monetizationprogress',
        (event) => {
          batcher.add({
            date: new Date().getTime(),
            value: Number((Number(event.detail.amount) * (10**(-1*event.detail.assetScale))).toFixed(event.detail.assetScale))
          });
        }
      );
  
      batcher.scheduleFlush();
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
        }, 6000)
      }
      else {
        alert('monetization meta tag is not correctly configured.')
      }
    }
  }

});
