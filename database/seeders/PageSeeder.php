<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_title'   => 'Privacy Policy',
                'page_content' => '<h2>Privacy Policy</h2>
<p>Last updated: January 2025</p>
<p>At <strong>SN Nutrition</strong>, we are committed to protecting your personal information and your right to privacy.</p>
<h3>1. Information We Collect</h3>
<ul>
  <li>Name, email address, phone number</li>
  <li>Billing and shipping address</li>
  <li>Payment information (processed securely by our payment partners)</li>
  <li>Order history and preferences</li>
</ul>
<h3>2. How We Use Your Information</h3>
<ul>
  <li>Process and fulfill your orders</li>
  <li>Send order confirmations and shipping updates</li>
  <li>Respond to your comments and questions</li>
  <li>Send promotional communications (with your consent)</li>
</ul>
<h3>3. Data Security</h3>
<p>We implement industry-standard security measures. All payment transactions are encrypted using SSL technology.</p>
<h3>4. Contact Us</h3>
<p>Email: <a href="mailto:support@sn-nutrition.com">support@sn-nutrition.com</a></p>',
            ],

            [
                'page_title'   => 'Terms & Conditions',
                'page_content' => '<h2>Terms of Service</h2>
<p>Welcome to SN Nutrition. By accessing our website, creating an account, or placing an order, you confirm that you have read, understood and agree to be bound by these Terms &amp; Conditions. Please read them carefully.</p>

<h3>1. About Us &amp; What We Sell</h3>
<p>SN Nutrition ("we", "us", "our", the "Company") is a retailer of food supplements, cosmetics, personal and body care products, coffee, dark chocolate and other healthy food and nutrition products. We are based in Casablanca, Morocco, and ship to customers in Morocco, the United Kingdom and selected international destinations.</p>
<p><strong>Important:</strong> We are not a pharmacy. We do not sell prescription medicines, controlled drugs, or any product requiring a medical prescription. All products we sell are food supplements, cosmetics, personal care or food products intended for general consumer use, and no prescription is required to purchase them.</p>
<p>SN Nutrition is a licensed and registered importer and distributor, and our products are registered or notified with the relevant authorities for the markets in which we sell them, in accordance with applicable regulations.</p>

<h3>2. Eligibility</h3>
<ul>
  <li>You must be at least 18 years old to create an account or place an order.</li>
  <li>You agree to provide accurate, current and complete information when registering or ordering, and to keep it up to date.</li>
  <li>You are responsible for maintaining the confidentiality of your account login details and for all activity that occurs under your account.</li>
  <li>You must notify us immediately of any unauthorised use of your account.</li>
</ul>

<h3>3. Nature of Our Products: Important Health &amp; Food Information</h3>
<p>By purchasing, you acknowledge and agree that:</p>
<ul>
  <li>Food supplements are not a substitute for a varied and balanced diet or a healthy lifestyle, and should not be used as such.</li>
  <li>Our products are not intended to diagnose, treat, cure or prevent any disease or medical condition.</li>
  <li>You should not exceed the stated recommended daily dose or usage instructions shown on the product label.</li>
  <li>Cosmetic and personal care products are for external use only and must be used strictly in accordance with the instructions provided.</li>
  <li>Food and drink products (including coffee and chocolate) may contain or have been produced in facilities that handle allergens such as milk, nuts, soya, gluten and others. It is your responsibility to read the ingredient and allergen information on each product before purchase and consumption.</li>
  <li>Coffee and certain supplements contain caffeine. If you are sensitive to caffeine, pregnant, breastfeeding, or have a relevant medical condition, please check the label and consult a professional before use.</li>
  <li>If you are pregnant, breastfeeding, taking medication, under medical supervision, or have a known allergy or medical condition, you should consult a doctor or qualified healthcare professional before use.</li>
  <li>Any information provided on our website, packaging or marketing materials is for general informational purposes only and does not constitute medical or dietary advice.</li>
</ul>
<p>By placing an order on our website, you confirm that, where relevant, you have consulted a doctor or qualified healthcare professional if you are pregnant, breastfeeding, taking any medication, or have any medical condition or allergy. You accept full responsibility for ensuring that the products you order are suitable for you.</p>

<h3>4. Orders &amp; Acceptance</h3>
<ul>
  <li>Placing an order constitutes an offer by you to purchase products under these Terms. A contract is formed only when we send you an order confirmation or dispatch the goods, whichever is earlier.</li>
  <li>We reserve the right to refuse, cancel or limit any order at our discretion, including in cases of suspected fraud, stock unavailability, pricing or description errors, or where we are unable to ship to your location.</li>
  <li>If we cancel an order you have already paid for, we will refund the amount paid in full.</li>
</ul>

<h3>5. Pricing &amp; Payment</h3>
<ul>
  <li>Prices are displayed on the website and may be shown in multiple currencies. The currency and final price applicable to your order will be confirmed at checkout.</li>
  <li>Prices may change at any time, but changes will not affect orders we have already confirmed.</li>
  <li>We make every effort to ensure prices are accurate. If we discover a pricing error, we will contact you and give you the option to confirm the order at the correct price or cancel it.</li>
  <li>Payment must be received in full before goods are dispatched, unless a cash-on-delivery option is expressly offered for your location.</li>
  <li>You are responsible for any currency conversion fees or charges applied by your bank or payment provider.</li>
</ul>

<h3>6. Shipping, Delivery &amp; Customs</h3>
<ul>
  <li>We deliver using Sendit as our delivery partner. We reserve the right to change our delivery company at any time without prior notice.</li>
  <li>Estimated delivery time is between 24 and 48 hours, depending on the city and the time of day your order was submitted. Orders placed later in the day may be processed the following day.</li>
  <li>Delivery times are estimates only and are not guaranteed. Delays caused by carriers, customs or events outside our control are not our responsibility.</li>
  <li>Please inspect your parcel on arrival. If the product is visibly damaged, please do not accept it from the delivery driver and ask them to return it to us. This is the quickest way for us to arrange a replacement or refund.</li>
  <li>For international orders, you are the importer of record and are responsible for paying any import duties, taxes, customs charges or fees levied by the destination country. These are not included in our prices.</li>
  <li>It is your responsibility to ensure that the products you order can be lawfully imported into your country.</li>
  <li>Risk of loss or damage to the products passes to you upon delivery to the address you provided.</li>
</ul>

<h3>7. Returns, Refunds &amp; Cancellations</h3>
<p>Because our products are consumable food, supplement, cosmetic and personal care items, strict hygiene, safety and food-safety rules apply:</p>
<ul>
  <li>We only refund products that are returned unopened, unused and in their original sealed condition. Opened, used or unsealed products cannot be refunded.</li>
  <li>If the product is visibly damaged on arrival, please do not accept it from the delivery driver and ask them to return it. If a damaged item is refused at the door, we will arrange a replacement or refund.</li>
  <li>Perishable goods (including coffee, chocolate and other food products) are exempt from cancellation and return once dispatched, except where faulty or incorrectly supplied.</li>
</ul>
<p><strong>How to request a refund:</strong></p>
<ul>
  <li>Contact us at <a href="mailto:contact@sn-nutrition.com">contact@sn-nutrition.com</a> with your order number and, where relevant, photographs of the issue.</li>
  <li>We aim to review and respond to your request within 24 to 72 hours.</li>
  <li>Once your refund case has been processed and approved, payment will be returned to a local bank account within 3 working days.</li>
  <li>Refunds are issued to the original payment method or nominated local bank account. We are not responsible for the original shipping costs or return postage except where the product was faulty or incorrectly supplied.</li>
</ul>

<h3>8. Your Statutory Consumer Rights</h3>
<p>Nothing in these Terms removes or limits any legal rights you have as a consumer that cannot be excluded under the law of your country of residence.</p>

<h3>9. Limitation of Liability</h3>
<ul>
  <li>To the fullest extent permitted by law, we are not liable for any harm, adverse reaction, illness or loss arising from misuse of our products, failure to follow the usage instructions or recommended dose, use despite a contraindicated medical condition, or failure to check ingredient and allergen information.</li>
  <li>We are not liable for indirect, incidental, special or consequential losses, or for loss of profit, arising from the use of our website or products.</li>
  <li>Our total liability for any claim relating to a product or order shall not exceed the amount you paid for that product or order.</li>
  <li>Nothing in these Terms excludes or limits our liability for death or personal injury caused by our proven negligence, for fraud, or for any other liability that cannot lawfully be excluded.</li>
</ul>

<h3>10. Indemnity</h3>
<p>You agree to indemnify and hold harmless SN Nutrition, its owners, employees and partners from any claims, losses, liabilities or expenses arising from your breach of these Terms, your misuse of our products, or your violation of any law or third-party right.</p>

<h3>11. Intellectual Property</h3>
<p>All content on our website, including text, logos, branding, product images, photography and design, is owned by or licensed to SN Nutrition and is protected by intellectual property law. You may not copy, reproduce, distribute or use any of it without our prior written permission.</p>

<h3>12. Acceptable Use</h3>
<ul>
  <li>You agree not to use our website unlawfully, fraudulently, or in any way that could damage, disable or impair it.</li>
  <li>You agree not to attempt to gain unauthorised access to our systems, accounts or data.</li>
  <li>You agree not to resell our products without our prior written authorisation.</li>
</ul>

<h3>13. Privacy &amp; Data Protection</h3>
<p>We take the protection of your personal data seriously and handle it in accordance with applicable data protection law. Please review our Privacy Policy for full details of how we collect, use and protect your information.</p>

<h3>14. Third-Party Links</h3>
<p>Our website may contain links to third-party websites. We are not responsible for the content, products or practices of those sites, and a link does not imply our endorsement.</p>

<h3>15. Force Majeure</h3>
<p>We are not liable for any delay or failure to perform our obligations where this is caused by events beyond our reasonable control, including but not limited to natural disasters, strikes, carrier failures, customs delays, supply shortages, or government action.</p>

<h3>16. Changes to These Terms</h3>
<p>We may update these Terms from time to time. The version published on our website at the time you place an order is the version that applies to that order. Your continued use of our website after changes are published constitutes acceptance of the revised Terms.</p>

<h3>17. Governing Law &amp; Disputes</h3>
<p>These Terms are governed by and construed in accordance with the laws of the Kingdom of Morocco, and the courts of Casablanca shall have jurisdiction, save where mandatory consumer law in your country of residence provides otherwise. We encourage you to contact us first so we can try to resolve any issue amicably before pursuing formal proceedings.</p>

<h3>18. Severability &amp; Entire Agreement</h3>
<p>If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions will continue in full force. These Terms, together with our Privacy Policy, constitute the entire agreement between you and us regarding your use of our website and purchase of our products.</p>

<h3>19. Contact Us</h3>
<p>If you have any questions about these Terms, please contact us:</p>
<ul>
  <li>✉ Email: <a href="mailto:contact@sn-nutrition.com">contact@sn-nutrition.com</a></li>
  <li>📞 Phone: +44 7824 739607</li>
  <li>🗺️ Address: Casablanca, Morocco</li>
</ul>
<p>By using SN Nutrition\'s website or purchasing our products, you acknowledge that you have read, understood and agree to these Terms &amp; Conditions.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => Str::slug($pageData['page_title'])],
                [
                    'page_title'   => $pageData['page_title'],
                    'slug'         => Str::slug($pageData['page_title']),
                    'page_content' => $pageData['page_content'],
                    'status'       => Page::STATUS['ACTIVE'],
                ]
            );
        }
    }
}
