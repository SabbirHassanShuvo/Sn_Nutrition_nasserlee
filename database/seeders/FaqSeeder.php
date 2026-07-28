<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Faq;
use App\Models\FaqCategory;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Orders & Shipping' => [
                [
                    'question' => 'How long does delivery take?',
                    'answer'   => 'Delivery typically takes 3–7 business days for most locations within India. Metro cities like Mumbai, Delhi, Bangalore, and Chennai usually receive orders within 2–4 days. Remote areas may take 7–10 business days. You will receive an estimated delivery date at checkout.',
                    'priority' => 1,
                ],
                [
                    'question' => 'Do you offer free shipping?',
                    'answer'   => 'Yes, we offer free shipping on all orders above ₹499. For orders below ₹499, a flat shipping fee of ₹40 applies. We also run occasional promotions where free shipping is offered on all orders – check our homepage for current offers.',
                    'priority' => 2,
                ],
                [
                    'question' => 'Can I track my order?',
                    'answer'   => 'Absolutely. Once your order is shipped, you will receive an SMS and email with a unique tracking link and tracking ID. You can use this link to see real-time updates on your order\'s location and estimated delivery time. You can also track your order directly from your account dashboard on our website.',
                    'priority' => 3,
                ],
            ],

            'Product & Ingredients' => [
                [
                    'question' => 'Are your products 100% natural?',
                    'answer'   => 'Yes, all SN-Nutrition products are formulated with natural, clinically studied ingredients. We do not use artificial colors, preservatives, or fillers. Each product\'s ingredient list is fully disclosed on the product page and packaging for complete transparency.',
                    'priority' => 1,
                ],
                [
                    'question' => 'Do your products contain allergens?',
                    'answer'   => 'Some of our products may contain common allergens like nuts, soy, gluten, or dairy. We clearly label all potential allergens on the product page and packaging. If you have a known allergy, please check the ingredients carefully or contact our support team before ordering.',
                    'priority' => 2,
                ],
                [
                    'question' => 'Are your products vegetarian/vegan?',
                    'answer'   => 'Most of our products are 100% vegetarian and vegan-friendly. Exceptions are clearly marked (e.g., products containing honey or gelatin). Look for the \'Vegan\' badge on the product listing. You can also filter products by dietary preference on our website.',
                    'priority' => 3,
                ],
            ],

            'Shipping Policy' => [
                [
                    'question' => 'What is the estimated delivery time?',
                    'answer'   => 'Standard delivery within India takes 3–7 business days from the date of dispatch. For example, an order placed on Monday will typically arrive by Friday or the following Monday. During peak seasons (festivals, sales), delivery may take 7–10 business days. Express shipping is available at checkout for an additional fee (delivery in 1–3 days).',
                    'priority' => 1,
                ],
                [
                    'question' => 'Do you ship internationally?',
                    'answer'   => 'Currently, we ship only within India. We are planning to expand to select international markets (UAE, Singapore, USA) by early 2027. Please subscribe to our newsletter to receive updates when international shipping becomes available.',
                    'priority' => 2,
                ],
                [
                    'question' => 'What happens if my package is lost?',
                    'answer'   => 'If your package is lost in transit (no tracking updates for 7+ days after dispatch), please contact our support team at shipping@sn-nutrition.com or call +91-XXXXXXXXXX. We will file a complaint with the courier partner. Once the courier confirms the loss, we will either issue a full refund or send a free replacement immediately.',
                    'priority' => 3,
                ],
            ],

            'Returns & Refunds' => [
                [
                    'question' => 'What is your return policy?',
                    'answer'   => 'We accept returns within 7 days of delivery for products that are damaged, expired, incorrect, or defective. To initiate a return, log in to your account, go to \'My Orders\', and click \'Return Request\'. You can also email support@sn-nutrition.com with your order ID and photos of the issue. Return shipping is free for eligible returns. We do not accept returns for products that have been opened or used unless they are defective.',
                    'priority' => 1,
                ],
                [
                    'question' => 'How long does the refund process take?',
                    'answer'   => 'Once we receive and inspect your returned product (typically within 3–5 business days after pickup), we will process your refund. Refunds to credit/debit cards, UPI, or net banking usually take 5–7 business days to reflect in your account. For Cash on Delivery orders, we will issue a bank transfer or store credit – please provide your bank details when requesting the return.',
                    'priority' => 2,
                ],
                [
                    'question' => 'Can I exchange an item?',
                    'answer'   => 'Yes, exchanges are allowed for defective or incorrect products. For example, if you received a different product than what you ordered, you can request an exchange instead of a refund. Exchanges are processed free of cost. Simply select \'Exchange\' in the return request form. The replacement item will be shipped once the original item is picked up. Exchanges are subject to stock availability.',
                    'priority' => 3,
                ],
            ],
        ];

        foreach ($data as $categoryName => $faqs) {
            $category = FaqCategory::where('slug', Str::slug($categoryName))->first();

            if (!$category) {
                continue; // Category must exist (run FaqCategorySeeder first)
            }

            foreach ($faqs as $faqData) {
                Faq::updateOrCreate(
                    [
                        'question'        => $faqData['question'],
                        'faq_category_id' => $category->id,
                    ],
                    [
                        'answer'          => $faqData['answer'],
                        'priority'        => $faqData['priority'],
                        'status'          => Faq::STATUS['ACTIVE'],
                        'faq_category_id' => $category->id,
                    ]
                );
            }
        }
    }
}
