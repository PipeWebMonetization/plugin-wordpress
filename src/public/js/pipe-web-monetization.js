class Batcher {
  transactionsList = [];
  timeout = 1000;
  pluginId = "";
  paymentPointer = "";

  setPluginId(value) {
    this.pluginId = value;
  }

  setPaymentPointer(value) {
    this.paymentPointer = String(value).trim();
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
      (previousValue, currentValue) => previousValue + currentValue.value,
      0
    );
    const data = {
      pluginId: this.pluginId,
      paymentPointer: this.paymentPointer,
      date: new Date().getTime(),
      totalValue: totalValue,
      transactions: this.transactionsList,
    };
    await fetch(
      `https://94w4fmrdq3.execute-api.us-east-1.amazonaws.com/Dev/api/transactions`,
      {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    this.transactionsList = [];
  }
}

jQuery(function ($) {
  console.log(plugin_infos.version);

  function setupMetaTag(pointer) {
    $("head").append(pointer);
  }

  function setupPaywall() {
    $("body").toggleClass("blur");
    $("body").append(
      `<div class="main-container">
        <div class="main-container-child">
          <label class="paywall-title">Sorry :/</label>
          <label>This content is web monetized,</label>
          <label>set up a wallet to see this content!</label>
            <img src="` +
        images_variables.icon_eye_url +
        `" />
          <label>Access <a href="https://www.pipewebmonetization.com/" target="_blank">Pipe Web Monetization</a> for more information.</label>
          <div class="default-button">
          <a class="button-link" href="https://www.pipewebmonetization.com/" target="_blank" class="default-button">
              Learn More
          </a>
        </div>
      </div>`
    );
  }

  if (!ajax_variables.logged_in) {
    const pointer =
      "<meta name='monetization' content='" +
      $("#monetization").attr("name") +
      "' />";
    setupMetaTag(pointer);
    setupMonetizationListeners();
  }

  async function setupMonetizationListeners() {
    const batcher = new Batcher();

    batcher.setPluginId(plugin_options.pwm_plugin_id);
    batcher.setPaymentPointer(
      document
        .querySelector('meta[name="monetization"]')
        .getAttribute("content")
    );

    if (post_infos.post_categories) {
      post_infos.post_categories.forEach((category) => {
        if (
          category.slug == "pipe-category" &&
          document.monetization == undefined &&
          post_infos.is_home != "1"
        ) {
          setupPaywall();
        }
      });
    }

    if (!document.monetization) {
      return;
    }

    document.monetization.addEventListener("monetizationprogress", (event) => {
      batcher.add({
        date: new Date().getTime(),
        value: Number(
          (
            Number(event.detail.amount) *
            10 ** (-1 * event.detail.assetScale)
          ).toFixed(event.detail.assetScale)
        ),
        postId: post_infos.is_home != "1" ? post_infos.post_id : "",
        postTitle: post_infos.is_home != "1" ? post_infos.post_title : "",
      });
    });

    document.monetization.addEventListener("tip", (event) => {
      batcher.add({
        date: new Date().getTime(),
        value: Number(
          (
            Number(event.detail.amount) *
            10 ** (-1 * event.detail.assetScale)
          ).toFixed(event.detail.assetScale)
        ),
      });
    });

    batcher.scheduleFlush();
  }

  function simulateMonetization() {
    if (document.monetization) {
      const randomGuid = "c7ff7da9-8a41-4660-98a8-ca4df0176fbe";

      const meta = document.querySelector('meta[name="monetization"]');
      let metaContent = null;
      if (meta) {
        metaContent = meta.getAttribute("content");
      }

      if (metaContent) {
        const resolvedEndpoint = metaContent.replace(/^\$/, "https://");

        const monetizationstartEvent = new CustomEvent("monetizationstart", {
          detail: {
            requestId: randomGuid,
            id: randomGuid,
            metaContent,
            resolvedEndpoint,
          },
        });

        const monetizationprogressEvent = new CustomEvent(
          "monetizationprogress",
          {
            detail: {
              amount: "200000",
              assetCode: "USD",
              assetScale: 9,
            },
          }
        );

        document.monetization.dispatchEvent(monetizationstartEvent);
        document.monetization.dispatchEvent(monetizationprogressEvent);

        setInterval(() => {
          document.monetization.dispatchEvent(monetizationprogressEvent);
        }, 6000);
      } else {
        alert("monetization meta tag is not correctly configured.");
      }
    }
  }
});
