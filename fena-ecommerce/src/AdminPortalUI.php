<?php

namespace FenaCommerceGateway;

class AdminPortalUI
{
  public static function get($settings)
  {

    ?>
    <h2>Fena Payment Gateway</h2>
    <table class="form-table">
      <?php echo $settings; ?>
    </table>

    <h4>Payment Notification URL</h4>
    <pre><?php echo home_url('/wc-api/fena', 'https'); ?></pre>

    <h4>Redirect URL</h4>
    <pre><?php echo wc_get_endpoint_url('order-received', '', wc_get_checkout_url()); ?></pre>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.getElementById('woocommerce_fena_payment_dropdown');
        const terminalSecretField = document.getElementById('woocommerce_fena_payment_terminal_secret');
        const terminalIdField = document.getElementById('woocommerce_fena_payment_terminal_id');
        const selectedBank = document.getElementById('woocommerce_fena_payment_selected_bank');
        const selectedBankID = document.getElementById('woocommerce_fena_payment_selected_bankId');

        var bankDetails = {}; // Assign a global Variable so we can give the values coming from the endpoint to it.

        if (terminalIdField.value && terminalSecretField.value) {
          // Make an API call to fetch the data
          // Replace 'apiEndpoint' with the actual API endpoint URL
          fetch('https://epos.api.fena.co/open/company/bank-accounts/list', {
            headers: {
              'secret-key': terminalSecretField.value,
              'integration-id': terminalIdField.value
            }
          })
            .then(response => {
              if (!response.ok) {
                throw new Error('Network response was not OK.');
              }
              return response.json();
            })
            .then(data => {
              console.log(data);
              const verifiedBanks = data.data.docs.filter(item => item.status === "verified");
              const banks = {};

              verifiedBanks.forEach(item => {
                banks[item.name] = item.id;
              });

              bankDetails = banks; // Assign the got value to the global value.

              if (Object.keys(bankDetails).length === 1) {
                const singleBankName = Object.keys(bankDetails)[0];
                selectedBank.value = singleBankName;
                selectedBankID.value = bankDetails[singleBankName];
              }

              dropdown.innerHTML = "";
              verifiedBanks.forEach(item => {
                const newOption = document.createElement("option");
                newOption.value = item.name;
                newOption.text = item.name;
                dropdown.appendChild(newOption);
              });

              const compare = selectedBank.value;
              if (compare) {
                for (const option of dropdown.options) {
                  // Check if the option value matches the compare value
                  if (option.value === compare) {
                    // If there is a match, set the selected attribute
                    option.selected = true;
                  }
                }
              }
            })
            .catch(error => console.error("Error fetching data:", error));

          dropdown.addEventListener('change', function handleChange(event) {
            const selected = event.target.value;
            const selectedBankId = bankDetails[selected]; // Extract the ID from the saved bank details global

            selectedBank.value = selected;
            selectedBankID.value = selectedBankId;
          });
        }
      });


    </script>

    </script>

    <?php
  }
}
