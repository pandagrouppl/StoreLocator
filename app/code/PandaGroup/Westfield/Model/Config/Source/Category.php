<?php

namespace PandaGroup\Westfield\Model\Config\Source;

class Category extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $optionFactory;

    public function getAllOptions()
    {
        $this->_options = [
            [
                'label' => 'womens-fashion-accessories',
                'value' => 'womens-fashion-accessories'
            ],
            [
                'label' => 'womens-dresses',
                'value' => 'womens-dresses'
            ],
            [
                'label' => 'w-dresses-maxi-dresses',
                'value' => 'w-dresses-maxi-dresses'
            ],
            [
                'label' => 'w-dresses-cocktail-party',
                'value' => 'w-dresses-cocktail-party'
            ],
            [
                'label' => 'w-dresses-formal-dresses',
                'value' => 'w-dresses-formal-dresses'
            ],
            [
                'label' => 'w-dresses-casual',
                'value' => 'w-dresses-casual'
            ],
            [
                'label' => 'w-dresses-work',
                'value' => 'w-dresses-work'
            ],
            [
                'label' => 'womens-coats-jackets',
                'value' => 'womens-coats-jackets'
            ],
            [
                'label' => 'w-coats-jackets-leather',
                'value' => 'w-coats-jackets-leather'
            ],
            [
                'label' => 'w-coats-jackets-coats',
                'value' => 'w-coats-jackets-coats'
            ],
            [
                'label' => 'w-coats-jackets-trench-coats',
                'value' => 'w-coats-jackets-trench-coats'
            ],
            [
                'label' => 'w-coats-jackets-blazers',
                'value' => 'w-coats-jackets-blazers'
            ],
            [
                'label' => 'w-coats-jackets-casual',
                'value' => 'w-coats-jackets-casual'
            ],
            [
                'label' => 'w-coats-jackets-denim',
                'value' => 'w-coats-jackets-denim'
            ],
            [
                'label' => 'womens-skirts',
                'value' => 'womens-skirts'
            ],
            [
                'label' => 'w-skirts-long',
                'value' => 'w-skirts-long'
            ],
            [
                'label' => 'w-skirts-pencil',
                'value' => 'w-skirts-pencil'
            ],
            [
                'label' => 'w-skirts-mini',
                'value' => 'w-skirts-mini'
            ],
            [
                'label' => 'w-skirts-knee-length',
                'value' => 'w-skirts-knee-length'
            ],
            [
                'label' => 'womens-pants',
                'value' => 'womens-pants'
            ],
            [
                'label' => 'w-pants-jeans',
                'value' => 'w-pants-jeans'
            ],
            [
                'label' => 'womens-shorts',
                'value' => 'womens-shorts'
            ],
            [
                'label' => 'w-pants-casual',
                'value' => 'w-pants-casual'
            ],
            [
                'label' => 'w-pants-leggings',
                'value' => 'w-pants-leggings'
            ],
            [
                'label' => 'w-pants-jumpsuit',
                'value' => 'w-pants-jumpsuit'
            ],
            [
                'label' => 'w-pants-straight',
                'value' => 'w-pants-straight'
            ],
            [
                'label' => 'womens-tops',
                'value' => 'womens-tops'
            ],
            [
                'label' => 'w-tops-blouses',
                'value' => 'w-tops-blouses'
            ],
            [
                'label' => 'w-tops-shirts',
                'value' => 'w-tops-shirts'
            ],
            [
                'label' => 'w-tops-t-shirts',
                'value' => 'w-tops-t-shirts'
            ],
            [
                'label' => 'womens-knitwear',
                'value' => 'womens-knitwear'
            ],
            [
                'label' => 'w-tops-singlets',
                'value' => 'w-tops-singlets'
            ],
            [
                'label' => 'w-knitwear-jumpers',
                'value' => 'w-knitwear-jumpers'
            ],
            [
                'label' => 'womens-suiting',
                'value' => 'womens-suiting'
            ],
            [
                'label' => 'w-suiting-jackets',
                'value' => 'w-suiting-jackets'
            ],
            [
                'label' => 'w-suiting-pants',
                'value' => 'w-suiting-pants'
            ],
            [
                'label' => 'w-suiting-skirts',
                'value' => 'w-suiting-skirts'
            ],
            [
                'label' => 'womens-swimwear',
                'value' => 'womens-swimwear'
            ],
            [
                'label' => 'w-swim-one-piece',
                'value' => 'w-swim-one-piece'
            ],
            [
                'label' => 'w-swim-bikini',
                'value' => 'w-swim-bikini'
            ],
            [
                'label' => 'w-swim-beach-kaftan',
                'value' => 'w-swim-beach-kaftan'
            ],
            [
                'label' => 'w-swim-boardshorts',
                'value' => 'w-swim-boardshorts'
            ],
            [
                'label' => 'w-swim-separates',
                'value' => 'w-swim-separates'
            ],
            [
                'label' => 'w-swim-rashies',
                'value' => 'w-swim-rashies'
            ],
            [
                'label' => 'womens-exercise-active-wear',
                'value' => 'womens-exercise-active-wear'
            ],
            [
                'label' => 'w-exercise-pants',
                'value' => 'w-exercise-pants'
            ],
            [
                'label' => 'w-exercise-tops',
                'value' => 'w-exercise-tops'
            ],
            [
                'label' => 'w-exercise-shorts',
                'value' => 'w-exercise-shorts'
            ],
            [
                'label' => 'w-exercise-jackets',
                'value' => 'w-exercise-jackets'
            ],
            [
                'label' => 'w-exercise-compression',
                'value' => 'w-exercise-compression'
            ],
            [
                'label' => 'w-exercise-accessories',
                'value' => 'w-exercise-accessories'
            ],
            [
                'label' => 'womens-sleepwear-intimates',
                'value' => 'womens-sleepwear-intimates'
            ],
            [
                'label' => 'w-sleep-nighties',
                'value' => 'w-sleep-nighties'
            ],
            [
                'label' => 'w-sleep-pj-sets',
                'value' => 'w-sleep-pj-sets'
            ],
            [
                'label' => 'w-sleep-robes',
                'value' => 'w-sleep-robes'
            ],
            [
                'label' => 'womens-bras',
                'value' => 'womens-bras'
            ],
            [
                'label' => 'womens-bottoms',
                'value' => 'womens-bottoms'
            ],
            [
                'label' => 'womens-shapewear',
                'value' => 'womens-shapewear'
            ],
            [
                'label' => 'womens-hosiery',
                'value' => 'womens-hosiery'
            ],
            [
                'label' => 'w-maternity-lingerie-underwear',
                'value' => 'w-maternity-lingerie-underwear'
            ],
            [
                'label' => 'womens-lingerie',
                'value' => 'womens-lingerie'
            ],
            [
                'label' => 'womens-maternity',
                'value' => 'womens-maternity'
            ],
            [
                'label' => 'w-tops-maternity',
                'value' => 'w-tops-maternity'
            ],
            [
                'label' => 'w-bottoms-maternity',
                'value' => 'w-bottoms-maternity'
            ],
            [
                'label' => 'w-dresses-maternity',
                'value' => 'w-dresses-maternity'
            ],
            [
                'label' => 'w-skirts-maternity',
                'value' => 'w-skirts-maternity'
            ],
            [
                'label' => 'w-coats-jackets-maternity',
                'value' => 'w-coats-jackets-maternity'
            ],
            [
                'label' => 'w-maternity-lingerie-underwear',
                'value' => 'w-maternity-lingerie-underwear'
            ],
            [
                'label' => 'womens-plus-size',
                'value' => 'womens-plus-size'
            ],
            [
                'label' => 'womens-plus-tops',
                'value' => 'womens-plus-tops'
            ],
            [
                'label' => 'womens-plus-bottoms',
                'value' => 'womens-plus-bottoms'
            ],
            [
                'label' => 'womens-plus-dresses',
                'value' => 'womens-plus-dresses'
            ],
            [
                'label' => 'womens-plus-skirts',
                'value' => 'womens-plus-skirts'
            ],
            [
                'label' => 'womens-plus-coats-jackets',
                'value' => 'womens-plus-coats-jackets'
            ],
            [
                'label' => 'bl-handbags',
                'value' => 'bl-handbags'
            ],
            [
                'label' => 'bl-handbags-clutch',
                'value' => 'bl-handbags-clutch'
            ],
            [
                'label' => 'bl-handbags-shoulder',
                'value' => 'bl-handbags-shoulder'
            ],
            [
                'label' => 'bl-handbags-tote',
                'value' => 'bl-handbags-tote'
            ],
            [
                'label' => 'bl-handbags-cross-body',
                'value' => 'bl-handbags-cross-body'
            ],
            [
                'label' => 'bl-handbags-satchel',
                'value' => 'bl-handbags-satchel'
            ],
            [
                'label' => 'bl-womens-purses',
                'value' => 'bl-womens-purses'
            ],
            [
                'label' => 'womens-jewellery',
                'value' => 'womens-jewellery'
            ],
            [
                'label' => 'womens-necklaces-pendants',
                'value' => 'womens-necklaces-pendants'
            ],
            [
                'label' => 'womens-rings',
                'value' => 'womens-rings'
            ],
            [
                'label' => 'womens-bracelets',
                'value' => 'womens-bracelets'
            ],
            [
                'label' => 'womens-earrings',
                'value' => 'womens-earrings'
            ],
            [
                'label' => 'womens-charms-beads',
                'value' => 'womens-charms-beads'
            ],
            [
                'label' => 'womens-watches',
                'value' => 'womens-watches'
            ],
            [
                'label' => 'womens-accessories',
                'value' => 'womens-accessories'
            ],
            [
                'label' => 'womens-belts',
                'value' => 'womens-belts'
            ],
            [
                'label' => 'womens-sunglasses',
                'value' => 'womens-sunglasses'
            ],
            [
                'label' => 'womens-hats-headwear',
                'value' => 'womens-hats-headwear'
            ],
            [
                'label' => 'womens-scarves-wraps',
                'value' => 'womens-scarves-wraps'
            ],
            [
                'label' => 'womens-gloves',
                'value' => 'womens-gloves'
            ],
            [
                'label' => 'w-fashion-jewellery-earrings',
                'value' => 'w-fashion-jewellery-earrings'
            ],
            [
                'label' => 'w-fashion-jewellery-brace-bangle',
                'value' => 'w-fashion-jewellery-brace-bangle'
            ],
            [
                'label' => 'w-fashion-jewellery-rings',
                'value' => 'w-fashion-jewellery-rings'
            ],
            [
                'label' => 'w-fj-necklaces-pendants',
                'value' => 'w-fj-necklaces-pendants'
            ],
            [
                'label' => 'womens-hair-accessories',
                'value' => 'womens-hair-accessories'
            ],
            [
                'label' => 'womens-shoes-footwear',
                'value' => 'womens-shoes-footwear'
            ],
            [
                'label' => 'womens-flats',
                'value' => 'womens-flats'
            ],
            [
                'label' => 'womens-heels',
                'value' => 'womens-heels'
            ],
            [
                'label' => 'womens-boots',
                'value' => 'womens-boots'
            ],
            [
                'label' => 'womens-sneakers',
                'value' => 'womens-sneakers'
            ],
            [
                'label' => 'mens-fashion-accessories',
                'value' => 'mens-fashion-accessories'
            ],
            [
                'label' => 'mens-coats-jackets',
                'value' => 'mens-coats-jackets'
            ],
            [
                'label' => 'm-coats-leather',
                'value' => 'm-coats-leather'
            ],
            [
                'label' => 'm-coats-blazers',
                'value' => 'm-coats-blazers'
            ],
            [
                'label' => 'm-coats-casual-jackets',
                'value' => 'm-coats-casual-jackets'
            ],
            [
                'label' => 'm-coats-coats',
                'value' => 'm-coats-coats'
            ],
            [
                'label' => 'mens-suits',
                'value' => 'mens-suits'
            ],
            [
                'label' => 'm-suits-business',
                'value' => 'm-suits-business'
            ],
            [
                'label' => 'm-suits-jackets',
                'value' => 'm-suits-jackets'
            ],
            [
                'label' => 'm-suits-vest',
                'value' => 'm-suits-vest'
            ],
            [
                'label' => 'mens-shirts',
                'value' => 'mens-shirts'
            ],
            [
                'label' => 'm-shirts-business-shirts',
                'value' => 'm-shirts-business-shirts'
            ],
            [
                'label' => 'm-shirts-short-sleeve',
                'value' => 'm-shirts-short-sleeve'
            ],
            [
                'label' => 'm-shirts-casual',
                'value' => 'm-shirts-casual'
            ],
            [
                'label' => 'mens-tops',
                'value' => 'mens-tops'
            ],
            [
                'label' => 'm-tops-t-shirts',
                'value' => 'm-tops-t-shirts'
            ],
            [
                'label' => 'm-tops-jumpers',
                'value' => 'm-tops-jumpers'
            ],
            [
                'label' => 'm-tops-singlets',
                'value' => 'm-tops-singlets'
            ],
            [
                'label' => 'm-tops-knitwear',
                'value' => 'm-tops-knitwear'
            ],
            [
                'label' => 'mens-pants',
                'value' => 'mens-pants'
            ],
            [
                'label' => 'm-pants-casual',
                'value' => 'm-pants-casual'
            ],
            [
                'label' => 'm-pants-dress',
                'value' => 'm-pants-dress'
            ],
            [
                'label' => 'mens-jeans',
                'value' => 'mens-jeans'
            ],
            [
                'label' => 'mens-shorts',
                'value' => 'mens-shorts'
            ],
            [
                'label' => 'mens-exercise-active-wear',
                'value' => 'mens-exercise-active-wear'
            ],
            [
                'label' => 'm-exercise-pants',
                'value' => 'm-exercise-pants'
            ],
            [
                'label' => 'm-exercise-shorts',
                'value' => 'm-exercise-shorts'
            ],
            [
                'label' => 'm-exercise-tops',
                'value' => 'm-exercise-tops'
            ],
            [
                'label' => 'm-exercise-compression',
                'value' => 'm-exercise-compression'
            ],
            [
                'label' => 'mens-swimwear',
                'value' => 'mens-swimwear'
            ],
            [
                'label' => 'm-exercise-accessories',
                'value' => 'm-exercise-accessories'
            ],
            [
                'label' => 'mens-underwear-socks',
                'value' => 'mens-underwear-socks'
            ],
            [
                'label' => 'm-underwear-boxers',
                'value' => 'm-underwear-boxers'
            ],
            [
                'label' => 'm-underwear-trunks',
                'value' => 'm-underwear-trunks'
            ],
            [
                'label' => 'm-underwear-briefs',
                'value' => 'm-underwear-briefs'
            ],
            [
                'label' => 'm-underwear-socks',
                'value' => 'm-underwear-socks'
            ],
            [
                'label' => 'm-underwear-thermal',
                'value' => 'm-underwear-thermal'
            ],
            [
                'label' => 'mens-sleep-loungewear',
                'value' => 'mens-sleep-loungewear'
            ],
            [
                'label' => 'm-sleep-robes',
                'value' => 'm-sleep-robes'
            ],
            [
                'label' => 'm-sleep-pj-tops',
                'value' => 'm-sleep-pj-tops'
            ],
            [
                'label' => 'm-sleep-pj-bottoms',
                'value' => 'm-sleep-pj-bottoms'
            ],
            [
                'label' => 'm-sleep-pj-sets',
                'value' => 'm-sleep-pj-sets'
            ],
            [
                'label' => 'mens-jewellery',
                'value' => 'mens-jewellery'
            ],
            [
                'label' => 'mens-rings',
                'value' => 'mens-rings'
            ],
            [
                'label' => 'mens-fashion-jewellery',
                'value' => 'mens-fashion-jewellery'
            ],
            [
                'label' => 'mens-necklaces-pendants',
                'value' => 'mens-necklaces-pendants'
            ],
            [
                'label' => 'mens-bracelets',
                'value' => 'mens-bracelets'
            ],
            [
                'label' => 'm-watches-dress',
                'value' => 'm-watches-dress'
            ],
            [
                'label' => 'm-watches-sport',
                'value' => 'm-watches-sport'
            ],
            [
                'label' => 'mens-accessories',
                'value' => 'mens-accessories'
            ],
            [
                'label' => 'mens-hats',
                'value' => 'mens-hats'
            ],
            [
                'label' => 'mens-scarves',
                'value' => 'mens-scarves'
            ],
            [
                'label' => 'mens-ties',
                'value' => 'mens-ties'
            ],
            [
                'label' => 'mens-gloves',
                'value' => 'mens-gloves'
            ],
            [
                'label' => 'mens-belts',
                'value' => 'mens-belts'
            ],
            [
                'label' => 'mens-cufflinks',
                'value' => 'mens-cufflinks'
            ],
            [
                'label' => 'mens-sunglasses',
                'value' => 'mens-sunglasses'
            ],
            [
                'label' => 'bl-mens-wallets',
                'value' => 'bl-mens-wallets'
            ],
            [
                'label' => 'mens-grooming',
                'value' => 'mens-grooming'
            ],
            [
                'label' => 'bh-mens-skin-care',
                'value' => 'bh-mens-skin-care'
            ],
            [
                'label' => 'mens-haircare',
                'value' => 'mens-haircare'
            ],
            [
                'label' => 'shaving-shavers-trimmers',
                'value' => 'shaving-shavers-trimmers'
            ],
            [
                'label' => 'perfume-mens',
                'value' => 'perfume-mens'
            ],
            [
                'label' => 'mens-shoes-footwear',
                'value' => 'mens-shoes-footwear'
            ],
            [
                'label' => 'mens-dress-shoes',
                'value' => 'mens-dress-shoes'
            ],
            [
                'label' => 'mens-casual',
                'value' => 'mens-casual'
            ],
            [
                'label' => 'mens-boots',
                'value' => 'mens-boots'
            ],
            [
                'label' => 'mens-sneakers',
                'value' => 'mens-sneakers'
            ],
            [
                'label' => 'kids-babies',
                'value' => 'kids-babies'
            ],
            [
                'label' => 'kids-girls-clothes',
                'value' => 'kids-girls-clothes'
            ],
            [
                'label' => 'girls-tops',
                'value' => 'girls-tops'
            ],
            [
                'label' => 'girls-pants',
                'value' => 'girls-pants'
            ],
            [
                'label' => 'girls-skirts-dresses',
                'value' => 'girls-skirts-dresses'
            ],
            [
                'label' => 'girls-outerwear',
                'value' => 'girls-outerwear'
            ],
            [
                'label' => 'girls-pyjamas',
                'value' => 'girls-pyjamas'
            ],
            [
                'label' => 'girls-swimwear',
                'value' => 'girls-swimwear'
            ],
            [
                'label' => 'girls-underwear',
                'value' => 'girls-underwear'
            ],
            [
                'label' => 'girls-jewellery',
                'value' => 'girls-jewellery'
            ],
            [
                'label' => 'girls-footwear',
                'value' => 'girls-footwear'
            ],
            [
                'label' => 'girls-accessories',
                'value' => 'girls-accessories'
            ],
            [
                'label' => 'kids-boys-clothes',
                'value' => 'kids-boys-clothes'
            ],
            [
                'label' => 'boys-tops',
                'value' => 'boys-tops'
            ],
            [
                'label' => 'boys-pants',
                'value' => 'boys-pants'
            ],
            [
                'label' => 'boys-outerwear',
                'value' => 'boys-outerwear'
            ],
            [
                'label' => 'boys-pyjamas',
                'value' => 'boys-pyjamas'
            ],
            [
                'label' => 'boys-swimwear',
                'value' => 'boys-swimwear'
            ],
            [
                'label' => 'boys-underwear',
                'value' => 'boys-underwear'
            ],
            [
                'label' => 'boys-jewellery',
                'value' => 'boys-jewellery'
            ],
            [
                'label' => 'boys-footwear',
                'value' => 'boys-footwear'
            ],
            [
                'label' => 'boys-accessories',
                'value' => 'boys-accessories'
            ],
            [
                'label' => 'kids-bags',
                'value' => 'kids-bags'
            ],
            [
                'label' => 'kids-backpacks',
                'value' => 'kids-backpacks'
            ],
            [
                'label' => 'k-babies',
                'value' => 'k-babies'
            ],
            [
                'label' => 'k-babies-clothing',
                'value' => 'k-babies-clothing'
            ],
            [
                'label' => 'kids-bathtime',
                'value' => 'kids-bathtime'
            ],
            [
                'label' => 'kids-nursery',
                'value' => 'kids-nursery'
            ],
            [
                'label' => 'kids-home',
                'value' => 'kids-home'
            ],
            [
                'label' => 'kids-car',
                'value' => 'kids-car'
            ],
            [
                'label' => 'kids-prams',
                'value' => 'kids-prams'
            ],
            [
                'label' => 'toddler-baby-footwear',
                'value' => 'toddler-baby-footwear'
            ],
            [
                'label' => 'kids-feeding',
                'value' => 'kids-feeding'
            ],
            [
                'label' => 'shoes-footwear',
                'value' => 'shoes-footwear'
            ],
            [
                'label' => 'womens-shoes-footwear',
                'value' => 'womens-shoes-footwear'
            ],
            [
                'label' => 'womens-flats',
                'value' => 'womens-flats'
            ],
            [
                'label' => 'womens-heels',
                'value' => 'womens-heels'
            ],
            [
                'label' => 'womens-boots',
                'value' => 'womens-boots'
            ],
            [
                'label' => 'womens-sneakers',
                'value' => 'womens-sneakers'
            ],
            [
                'label' => 'mens-shoes-footwear',
                'value' => 'mens-shoes-footwear'
            ],
            [
                'label' => 'mens-dress-shoes',
                'value' => 'mens-dress-shoes'
            ],
            [
                'label' => 'mens-casual',
                'value' => 'mens-casual'
            ],
            [
                'label' => 'mens-boots',
                'value' => 'mens-boots'
            ],
            [
                'label' => 'mens-sneakers',
                'value' => 'mens-sneakers'
            ],
            [
                'label' => 'kids-footwear',
                'value' => 'kids-footwear'
            ],
            [
                'label' => 'girls-footwear',
                'value' => 'girls-footwear'
            ],
            [
                'label' => 'boys-footwear',
                'value' => 'boys-footwear'
            ],
            [
                'label' => 'toddler-baby-footwear',
                'value' => 'toddler-baby-footwear'
            ],
            [
                'label' => 'jewellery-watches',
                'value' => 'jewellery-watches'
            ],
            [
                'label' => 'womens-jewellery',
                'value' => 'womens-jewellery'
            ],
            [
                'label' => 'mens-jewellery',
                'value' => 'mens-jewellery'
            ],
            [
                'label' => 'kids-jewellery',
                'value' => 'kids-jewellery'
            ],
            [
                'label' => 'girls-jewellery',
                'value' => 'girls-jewellery'
            ],
            [
                'label' => 'boys-jewellery',
                'value' => 'boys-jewellery'
            ],
            [
                'label' => 'beauty-health',
                'value' => 'beauty-health'
            ],
            [
                'label' => 'mens-grooming',
                'value' => 'mens-grooming'
            ],
            [
                'label' => 'bh-mens-skin-care',
                'value' => 'bh-mens-skin-care'
            ],
            [
                'label' => 'mens-haircare',
                'value' => 'mens-haircare'
            ],
            [
                'label' => 'shaving-shavers-trimmers',
                'value' => 'shaving-shavers-trimmers'
            ],
            [
                'label' => 'perfume-mens',
                'value' => 'perfume-mens'
            ],
            [
                'label' => 'bh-hair-care',
                'value' => 'bh-hair-care'
            ],
            [
                'label' => 'hair-treatments',
                'value' => 'hair-treatments'
            ],
            [
                'label' => 'hair-equipment',
                'value' => 'hair-equipment'
            ],
            [
                'label' => 'hair-styling-products',
                'value' => 'hair-styling-products'
            ],
            [
                'label' => 'hair-shampoo-conditioner',
                'value' => 'hair-shampoo-conditioner'
            ],
            [
                'label' => 'hair-hair-colours',
                'value' => 'hair-hair-colours'
            ],
            [
                'label' => 'bh-womens-makeup',
                'value' => 'bh-womens-makeup'
            ],
            [
                'label' => 'hf-hands-nail-polish',
                'value' => 'hf-hands-nail-polish'
            ],
            [
                'label' => 'makeup-face',
                'value' => 'makeup-face'
            ],
            [
                'label' => 'makeup-brushes',
                'value' => 'makeup-brushes'
            ],
            [
                'label' => 'makeup-eyes',
                'value' => 'makeup-eyes'
            ],
            [
                'label' => 'makeup-lips',
                'value' => 'makeup-lips'
            ],
            [
                'label' => 'makeup-kits',
                'value' => 'makeup-kits'
            ],
            [
                'label' => 'access-makeup',
                'value' => 'access-makeup'
            ],
            [
                'label' => 'bh-womens-skin-care',
                'value' => 'bh-womens-skin-care'
            ],
            [
                'label' => 'wskin-cleansers-remover',
                'value' => 'wskin-cleansers-remover'
            ],
            [
                'label' => 'wskin-moisturisers-creams',
                'value' => 'wskin-moisturisers-creams'
            ],
            [
                'label' => 'wskin-masks',
                'value' => 'wskin-masks'
            ],
            [
                'label' => 'wskin-scrubs-exfoliators',
                'value' => 'wskin-scrubs-exfoliators'
            ],
            [
                'label' => 'wskin-suncare',
                'value' => 'wskin-suncare'
            ],
            [
                'label' => 'bh-bath',
                'value' => 'bh-bath'
            ],
            [
                'label' => 'bh-hands-feet',
                'value' => 'bh-hands-feet'
            ],
            [
                'label' => 'bh-body-moisturiser',
                'value' => 'bh-body-moisturiser'
            ],
            [
                'label' => 'wskin-tanning',
                'value' => 'wskin-tanning'
            ],
            [
                'label' => 'wskin-bath-shower',
                'value' => 'wskin-bath-shower'
            ],
            [
                'label' => 'bh-shaving-hair-removal',
                'value' => 'bh-shaving-hair-removal'
            ],
            [
                'label' => 'bh-perfume',
                'value' => 'bh-perfume'
            ],
            [
                'label' => 'perfume-womens',
                'value' => 'perfume-womens'
            ],
            [
                'label' => 'perfume-mens',
                'value' => 'perfume-mens'
            ],
            [
                'label' => 'bh-vitamins-supplements',
                'value' => 'bh-vitamins-supplements'
            ],
            [
                'label' => 'vitamins-v-multies',
                'value' => 'vitamins-v-multies'
            ],
            [
                'label' => 'vitamins-energy-sport',
                'value' => 'vitamins-energy-sport'
            ],
            [
                'label' => 'vitamins-nutritional-supp',
                'value' => 'vitamins-nutritional-supp'
            ],
            [
                'label' => 'bags-luggage',
                'value' => 'bags-luggage'
            ],
            [
                'label' => 'bl-handbags',
                'value' => 'bl-handbags'
            ],
            [
                'label' => 'bl-handbags-clutch',
                'value' => 'bl-handbags-clutch'
            ],
            [
                'label' => 'bl-handbags-cross-body',
                'value' => 'bl-handbags-cross-body'
            ],
            [
                'label' => 'bl-handbags-shoulder',
                'value' => 'bl-handbags-shoulder'
            ],
            [
                'label' => 'bl-handbags-satchel',
                'value' => 'bl-handbags-satchel'
            ],
            [
                'label' => 'bl-handbags-tote',
                'value' => 'bl-handbags-tote'
            ],
            [
                'label' => 'bl-womens-purses',
                'value' => 'bl-womens-purses'
            ],
            [
                'label' => 'bl-mens-wallets',
                'value' => 'bl-mens-wallets'
            ],
            [
                'label' => 'bl-mens-wallets',
                'value' => 'bl-mens-wallets'
            ],
            [
                'label' => 'bl-bags-packs',
                'value' => 'bl-bags-packs'
            ],
            [
                'label' => 'kids-backpacks',
                'value' => 'kids-backpacks'
            ],
            [
                'label' => 'bl-backpacks',
                'value' => 'bl-backpacks'
            ],
            [
                'label' => 'bl-laptop-bags',
                'value' => 'bl-laptop-bags'
            ],
            [
                'label' => 'bl-messenger-satchels',
                'value' => 'bl-messenger-satchels'
            ],
            [
                'label' => 'bl-business',
                'value' => 'bl-business'
            ],
            [
                'label' => 'bl-briefcases',
                'value' => 'bl-briefcases'
            ],
            [
                'label' => 'bl-diaries-compendium',
                'value' => 'bl-diaries-compendium'
            ],
            [
                'label' => 'bl-travel-luggage',
                'value' => 'bl-travel-luggage'
            ],
            [
                'label' => 'bl-carry-on-bags',
                'value' => 'bl-carry-on-bags'
            ],
            [
                'label' => 'bl-hard-suitcases',
                'value' => 'bl-hard-suitcases'
            ],
            [
                'label' => 'bl-soft-suitcases',
                'value' => 'bl-soft-suitcases'
            ],
            [
                'label' => 'bl-garment-bags',
                'value' => 'bl-garment-bags'
            ],
            [
                'label' => 'bl-toiletry-bags',
                'value' => 'bl-toiletry-bags'
            ],
            [
                'label' => 'bl-travel-accessories',
                'value' => 'bl-travel-accessories'
            ],
            [
                'label' => 'computers-electronics',
                'value' => 'computers-electronics'
            ],
            [
                'label' => 'c-cameras',
                'value' => 'c-cameras'
            ],
            [
                'label' => 'e-cameras-digital-slr',
                'value' => 'e-cameras-digital-slr'
            ],
            [
                'label' => 'e-digital-cameras',
                'value' => 'e-digital-cameras'
            ],
            [
                'label' => 'e-cameras-compact',
                'value' => 'e-cameras-compact'
            ],
            [
                'label' => 'e-cameras-video',
                'value' => 'e-cameras-video'
            ],
            [
                'label' => 'e-cameras-accessories',
                'value' => 'e-cameras-accessories'
            ],
            [
                'label' => 'e-hi-fi-home-audio',
                'value' => 'e-hi-fi-home-audio'
            ],
            [
                'label' => 'c-home-earphones',
                'value' => 'c-home-earphones'
            ],
            [
                'label' => 'c-home-headphones',
                'value' => 'c-home-headphones'
            ],
            [
                'label' => 'e-hifi-speakers',
                'value' => 'e-hifi-speakers'
            ],
            [
                'label' => 'e-hifi-mini',
                'value' => 'e-hifi-mini'
            ],
            [
                'label' => 'e-hifi-amps-receivers',
                'value' => 'e-hifi-amps-receivers'
            ],
            [
                'label' => 'e-home-theatre',
                'value' => 'e-home-theatre'
            ],
            [
                'label' => 'e-portable-media-mp3',
                'value' => 'e-portable-media-mp3'
            ],
            [
                'label' => 'e-portable-accessories',
                'value' => 'e-portable-accessories'
            ],
            [
                'label' => 'c-games-consoles',
                'value' => 'c-games-consoles'
            ],
            [
                'label' => 'c-games',
                'value' => 'c-games'
            ],
            [
                'label' => 'c-consoles',
                'value' => 'c-consoles'
            ],
            [
                'label' => 'c-games-accessories',
                'value' => 'c-games-accessories'
            ],
            [
                'label' => 'computers',
                'value' => 'computers'
            ],
            [
                'label' => 'c-computers-desktops',
                'value' => 'c-computers-desktops'
            ],
            [
                'label' => 'c-computers-laptops',
                'value' => 'c-computers-laptops'
            ],
            [
                'label' => 'c-computers-tablets',
                'value' => 'c-computers-tablets'
            ],
            [
                'label' => 'c-computers-accessories',
                'value' => 'c-computers-accessories'
            ],
            [
                'label' => 'e-televisions',
                'value' => 'e-televisions'
            ],
            [
                'label' => 'e-tv-led',
                'value' => 'e-tv-led'
            ],
            [
                'label' => 'e-tv-lcd',
                'value' => 'e-tv-lcd'
            ],
            [
                'label' => 'e-tv-plasma',
                'value' => 'e-tv-plasma'
            ],
            [
                'label' => 'e-tv-accessories',
                'value' => 'e-tv-accessories'
            ],
            [
                'label' => 'e-phone-accessories',
                'value' => 'e-phone-accessories'
            ],
            [
                'label' => 'phone-smart',
                'value' => 'phone-smart'
            ],
            [
                'label' => 'phone-feature',
                'value' => 'phone-feature'
            ],
            [
                'label' => 'phone-accessories',
                'value' => 'phone-accessories'
            ],
            [
                'label' => 'navigation',
                'value' => 'navigation'
            ],
            [
                'label' => 'e-car-gps',
                'value' => 'e-car-gps'
            ],
            [
                'label' => 'navigation-handheld',
                'value' => 'navigation-handheld'
            ],
            [
                'label' => 'navigation-accessories',
                'value' => 'navigation-accessories'

            ]
        ];
        
        return $this->_options;
    }

    public function getOptionText($value)
    {
        foreach($this->getAllOptions() as $option){
            if($option['value'] == $value) {
                return $option['value'];
            }
        }
        
        return false;
    }
}
