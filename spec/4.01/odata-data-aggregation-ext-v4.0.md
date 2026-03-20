# OData Extension for Data Aggregation Version 4.

## Committee Specification 04

## 18 November 2025

**This stage:**

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs04/odata-data-aggregation-ext-v4.0-cs04.md
(Authoritative)

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs04/odata-data-aggregation-ext-v4.0-cs04.html
https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs04/odata-data-aggregation-ext-v4.0-cs04.pdf

**Previous stage:**

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs03/odata-data-aggregation-ext-v4.0-cs03.md

(Authoritative)
https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs03/odata-data-aggregation-ext-v4.0-cs03.html

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs03/odata-data-aggregation-ext-v4.0-cs03.pdf

**Latest stage:**

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/odata-data-aggregation-ext-v4.0.md (Authoritative)
https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/odata-data-aggregation-ext-v4.0.html

https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/odata-data-aggregation-ext-v4.0.pdf

**Technical Committee:**

OASIS Open Data Protocol (OData) TC

**Chairs:**

Ralf Handl (ralf.handl@sap.com), SAP SE
Michael Pizzo (mikep@microsoft.com), Microsoft

**Editors:**

Ralf Handl (ralf.handl@sap.com), SAP SE
Hubert Heijkers (hubert.heijkers@nl.ibm.com), IBM
Gerald Krause (gerald.krause@sap.com), SAP SE

Michael Pizzo (mikep@microsoft.com), Microsoft
Heiko Theißen (heiko.theissen@sap.com), SAP SE

Martin Zurmuehl (martin.zurmuehl@sap.com), SAP SE

**Additional artifacts:**

This document is one component of a Work Product that also includes:

```
ABNF components: OData Aggregation ABNF Construction Rules Version 4.0 and OData Aggregation ABNF Test
Cases : https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/cs04/abnf/
OData Aggregation Vocabulary:
```

```
https://docs.oasis-open.org/odata/odata-data-aggregation-
ext/v4.0/cs04/vocabularies/Org.OData.Aggregation.V1.json
https://docs.oasis-open.org/odata/odata-data-aggregation-
ext/v4.0/cs04/vocabularies/Org.OData.Aggregation.V1.xml
```
**Related work:**

This specification is related to:

```
OData Version 4.01. Edited by Michael Pizzo, Ralf Handl, and Martin Zurmuehl. A multi-part Work Product which
includes:
OData Version 4.01 Part 1: Protocol. Latest stage: https://docs.oasis-open.org/odata/odata/v4.01/odata-
v4.01-part1-protocol.html
OData Version 4.01 Part 2: URL Conventions. Latest stage: https://docs.oasis-
open.org/odata/odata/v4.01/odata-v4.01-part2-url-conventions.html
ABNF components: OData ABNF Construction Rules Version 4.01 and OData ABNF Test Cases.
https://docs.oasis-open.org/odata/odata/v4.01/os/abnf/
OData Vocabularies Version 4.0. Edited by Michael Pizzo, Ralf Handl, and Ram Jeyaraman. Latest stage:
https://docs.oasis-open.org/odata/odata-vocabularies/v4.0/odata-vocabularies-v4.0.html
OData Common Schema Definition Language (CSDL) JSON Representation Version 4.01. Edited by Michael
Pizzo, Ralf Handl, and Martin Zurmuehl. Latest stage: https://docs.oasis-open.org/odata/odata-csdl-
json/v4.01/odata-csdl-json-v4.01.html
OData Common Schema Definition Language (CSDL) XML Representation Version 4.01. Edited by Michael Pizzo,
Ralf Handl, and Martin Zurmuehl. Latest stage: https://docs.oasis-open.org/odata/odata-csdl-xml/v4.01/odata-csdl-
xml-v4.01.html
OData JSON Format Version 4.01. Edited by Ralf Handl, Mike Pizzo, and Mark Biamonte. Latest stage:
https://docs.oasis-open.org/odata/odata-json-format/v4.01/odata-json-format-v4.01.html
```
**Abstract:**

This specification adds basic grouping and aggregation functionality (e.g. sum, min, and max) to the Open Data Protocol
(OData) without changing any of the base principles of OData.

**Status:**

This document was last revised or approved by the OASIS Open Data Protocol (OData) TC on the above date. The

level of approval is also listed above. Check the “Latest stage” location noted above for possible later revisions of this
document. Any other numbered Versions and other technical work produced by the Technical Committee (TC) are listed

at https://groups.oasis-open.org/communities/tc-community-home2?CommunityKey=e7cac2a9-2d18-4640-b94d-
018dc7d3f0e2#technical.

TC members should send comments on this specification to the TC’s email list. Any individual may submit comments to
the TC by sending email to Technical-Committee-Comments@oasis-open.org. Please use a Subject line like “Comment

on OData Data Aggregation”.

This specification is provided under the RF on RAND Terms Mode of the OASIS IPR Policy, the mode chosen when the

Technical Committee was established. For information on whether any patents have been disclosed that may be
essential to implementing this specification, and any offers of patent licensing terms, please refer to the Intellectual
Property Rights section of the TC’s web page (https://www.oasis-open.org/committees/odata/ipr.php).

Note that any machine-readable content (Computer Language Definitions) declared Normative for this Work Product is
provided in separate plain text files. In the event of a discrepancy between any such plain text file and display content in
the Work Product’s prose narrative document(s), the content in the separate plain text file prevails.

**Key words:**

The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”, “SHOULD NOT”,

“RECOMMENDED”, “NOT RECOMMENDED”, “MAY”, and “OPTIONAL” in this document are to be interpreted as


described in BCP 14 **[RFC21 19 ]** and **[RFC8174]** when, and only when, they appear in all capitals, as shown here.

**Citation format:**

When referencing this specification the following citation format should be used:

**[OData-Data-Agg-v4.0]**

_OData Extension for Data Aggregation Version 4.0_. Edited by Ralf Handl, Hubert Heijkers, Gerald Krause, Michael
Pizzo, Heiko Theißen, and Martin Zurmuehl. 18 November 2025. OASIS Committee Specification 04. https://docs.oasis-
open.org/odata/odata-data-aggregation-ext/v4.0/cs04/odata-data-aggregation-ext-v4.0-cs04.html. Latest stage:
https://docs.oasis-open.org/odata/odata-data-aggregation-ext/v4.0/odata-data-aggregation-ext-v4.0.html.

**Notices**

Copyright © OASIS Open 2025. All Rights Reserved.

Distributed under the terms of the OASIS IPR Policy.

The name “OASIS” is a trademark of OASIS, the owner and developer of this specification, and should be used only to

refer to the organization and its official outputs.

For complete copyright information please see the full Notices section in an Appendix below.


# Table of Contents

```
1 Introduction
1.1 Changes from Earlier Versions
1.2 Glossary
1.2.1 Definitions of Terms
1.2.2 Acronyms and Abbreviations
1.2.3 Document Conventions
2 Overview
2.1 Example Data Model
2.2 Example Data
2.3 Example Use Cases
3 System Query Option $apply
3.1 Fundamentals of Input and Output Sets
3.1.1 Type, Structure and Context URL
3.1.2 Sameness and Order
3.1.3 Evaluation of Data Aggregation Paths
3.2 Basic Aggregation
3.2.1 Transformation aggregate
3.2.1.1 Aggregation Algorithm
3.2.1.2 Keyword as
3.2.1.3 Aggregation Methods
3.2.1.3.1 Standard Aggregation Method sum
3.2.1.3.2 Standard Aggregation Method min
3.2.1.3.3 Standard Aggregation Method max
3.2.1.3.4 Standard Aggregation Method average
3.2.1.3.5 Standard Aggregation Method countdistinct
3.2.1.3.6 Custom Aggregation Methods
3.2.1.4 Aggregate Expression $count
```
```
3.2.2 Transformation concat
3.2.3 Transformation groupby
3.2.3.1 Simple Grouping
3.3 Transformations Producing a Subset
3.3.1 Top/bottom transformations
3.3.1.1 Transformations bottomcount and topcount
3.3.1.2 Transformations bottompercent and toppercent
3.3.1.3 Transformations bottomsum and topsum
3.3.2 Transformation filter
3.3.3 Transformation orderby
3.3.4 Transformation search
```
```
3.3.5 Transformation skip
3.3.6 Transformation top
3.3.7 Stable Total Order Before $skip and $top
3.4 One-to-One Transformations
3.4.1 Transformation identity
3.4.2 Transformation compute
3.5 Transformations Changing the Input Set Structure
```

```
3.5.1 Transformations join and outerjoin
3.6 Expressions Evaluable on a Collection
3.6.1 Function aggregate
3.6.2 Expression $count
3.7 Function isdefined
3.8 Evaluating $apply as an Expand and Select Option
3.9 ABNF for Extended URL Conventions
```
4 Cross-Joins and Aggregation

5 Vocabulary for Data Aggregation

```
5.1 Aggregation Capabilities
5.2 Custom Aggregates
5.3 Context-Defining Properties
5.4 Annotation Example
5.5 Hierarchies
5.5.1 Recursive Hierarchy
5.5.1.1 Hierarchy Functions
5.5.2 Hierarchy Examples
5.6 Functions on Aggregated Entities
```
6 Hierarchical Transformations

```
6.1 Common Parameters for Hierarchical Transformations
6.2 Hierarchical Transformations Producing a Subset
6.2.1 Transformations ancestors and descendants
6.2.2 Transformation traverse
```
7 Examples

```
7.1 Requesting Distinct Values
7.2 Standard Aggregation Methods
7.3 Requesting Expanded Results
7.4 Requesting Custom Aggregates
7.5 Aliasing
7.6 Combining Transformations per Group
7.7 Model Functions as Set Transformations
7.8 Controlling Aggregation per Rollup Level
7.9 Aggregation in Recursive Hierarchies
7.10 Maintaining Recursive Hierarchies
7.11 Transformation Sequences
```
8 Conformance

A References

```
A.1 Normative References
```
B Acknowledgments

```
B.1 Special Thanks
B.2 Participants
```
C Revision History

D Notices


# 1 Introduction

This specification adds aggregation functionality to the Open Data Protocol (OData) without changing any of the base

principles of OData. It defines semantics and a representation for aggregation of data, especially:

```
Semantics and operations for querying aggregated data,
Results format for queries containing aggregated data,
Vocabulary terms to annotate what can be aggregated, and how.
```
## 1.1 Changes from Earlier Versions

Compared to the previous stage **[OData-Data-Agg-v4.0]** OASIS Committee Specification 03, this version makes the
following restrictions.

```
Section Restriction
```
```
After section 3.2.1.4 Keyword from removed
```
```
After section 3.2.3.1 Grouping with rollup removed
```
```
After section 3.4.2 Transformation addnested removed
```
```
After section 3.5.1 Transformation nest removed
```
```
Before section 5.5.1 Leveled Hierarchy removed
```
```
Section 6.1 Optional parameter removed
```
```
Section 6.2.2 Restricted to single-valued ParentNavigationProperty
```
```
After section 6.2.2 Grouping with rolluprecursive removed
```
## 1.2 Glossary

## 1.2.1 Definitions of Terms

This specification defines the following terms:

```
Aggregatable Expression – an expression not involving term casts and resulting in a value of a complex or entity or
an aggregatable primitive type
Aggregate Expression – argument of the aggregate transformation or function defined in section 3.2.1.
Aggregatable Primitive Type – a primitive type other than Edm.Stream or subtypes of Edm.Geography or
Edm.Geometry
Data Aggregation Path – a path that consists of one or more segments joined together by forward slashes ( / ).
Segments are names of declared or dynamic structural or navigation properties, or type-cast segments consisting
of the (optionally qualified) name of a structured type that is derived from the type identified by the preceding path
segment to reach properties declared by the derived type.
Expression – derived from the commonExpr rule (see [OData-ABNF] )
Single-Valued Property Path – property path ending in a single-valued primitive, complex, or navigation property
```
## 1.2.2 Acronyms and Abbreviations

The following non-exhaustive list contains variable names that are used throughout this document:

- collections of instances

```
S
```
```
A , B , C
```

- hierarchical collection
    - subset of nodes from a hierarchical collection
       - instances in a collection
- an instance in a hierarchical collection, called a node
- paths
- transformation sequence
- aggregate expression, defined in section 3.2.1.
- the collection that results from evaluating a data aggregation path relative to a collection , defined in
section 3.1.
- the collection that results from evaluating a data aggregation path relative to an instance , defined in
section 3.1.
- a transformation of a collection that injects grouping properties into every instance of the collection,
defined in section 3.2.3.
- instance containing a grouping property that represents a node , defined in section 6.2.

**1.2.3 Document Conventions**

Keywords defined by this specification use **this monospaced font**.

Some sections of this specification are illustrated with non-normative examples.

_Example 1: text describing an example uses this paragraph style_

```
Non-normative examples use this paragraph style.
```
All examples in this document are non-normative and informative only. Examples labeled with ⚠ contain advanced

concepts or make use of keywords that are defined only later in the text, they can be skipped at first reading.

All other text is normative unless otherwise labeled.

Paragraphs labeled 🚧 in this version of the specification contain restrictions that were not made in **[OData-Data-Agg-**

**v4.0]** OASIS Committee Specification 03. Also, some sections of **[OData-Data-Agg-v4.0]** OASIS Committee
Specification 03 are omitted from this version. In later OASIS standard versions these restrictions may be lifted again
and the omitted sections reintroduced.

The ABNF rules **[OData-ABNF]** have been simplified in this version to reflect these restrictions. Also, some members of
the OData Aggregation Vocabulary **[OData-VocAggr]** have been omitted from this version. These members are

referenced by **[OData-Data-Agg-v4.0]** OASIS Committee Specification 03 but not by this version.

```
H
H ′
u , v , w
x
p , q , r
T
α
Γ( A , p ) p A
```
```
γ ( u , p ) p u
```
```
Π G ( s )
```
```
σ ( x ) x
```

# 2 Overview

Open Data Protocol (OData) services expose a data model that describes the schema of the service in terms of the

Entity Data Model (EDM, see **[OData-CSDL]** ) and then allows for querying data in terms of this model. The responses
returned by an OData service are based on that data model and retain the relationships between the entities in the

model.

Extending the OData query features with simple aggregation capabilities avoids cluttering OData services with an

exponential number of explicitly modeled “aggregation level entities” or else restricting the consumer to a small subset of

predefined aggregations.

Adding the notion of aggregation to OData without changing any of the base principles in OData has two aspects:

1. Means for the consumer to query aggregated data on top of any given data model (for sufficiently capable data
    providers)
2. Means for the provider to annotate what data can be aggregated, and in which way, allowing consumers to avoid
    asking questions that the provider cannot answer

Implementing any of these two aspects is valuable in itself independent of the other, and implementing both provides
additional value for consumers. The provided aggregation annotations help a consumer understand more of the data
structure looking at the service’s exposed data model. The query extensions allow the consumers to explicitly express

the desired aggregation behavior for a particular query. They also allow consumers to formulate queries that utilize the
aggregation annotations.

## 2.1 Example Data Model

_Example 2: The following diagram depicts a simple model that is used throughout this document._

```
ID: Edm.String {id}
Amount: Edm.Decimal
```
```
Sale
```
```
Date: Edm.Date {id}
Month: Edm.String
Quarter: Edm.String
Year: Edm.Int
```
```
Time
```
```
ID: Edm.String {id}
Name: Edm.String
Country: Edm.String
```
```
Customer
```
```
ID: Edm.String {id}
Name: Edm.String
```
```
Category
```
```
ID: Edm.String {id}
Name: Edm.String
Color: Edm.String
TaxRate: Edm.Decimal
```
```
Product
```
```
ID: Edm.String {id}
Name: Edm.String
```
```
SalesOrganization
```
### 1

### *

### 1

### *

### *

### 1

### 0..

### *

### 1

### *

### 1

### *

```
Sales Sales
```
```
Customer Product
```
```
Products
```
```
Category
Time
```
```
SalesOrganization
```
```
Superordinate
```
```
Rating: Edm.Byte
```
```
FoodProduct
```
```
RatingClass: Edm.String
```
```
NonFoodProduct
```

_The_ **Amount** _property in the_ **Sale** _entity type is an aggregatable property, and the properties of the related entity types are groupable. These can
be arranged in hierarchies, for example:_

```
Product hierarchy based on groupable properties of the Category and Product entity types
Customer hierarchy based on Country and Customer
Time hierarchy based on Year , Month , and Date
SalesOrganization hierarchy based on the recursive association to itself
```
_In the context of Online Analytical Processing (OLAP), this model might be described in terms of a Sales “cube” with an Amount “measure” and
three “dimensions”. This document will avoid such terms, as they are heavily overloaded._

Query extensions and descriptive annotations can be applied to normalized schemas as well as partly or fully

denormalized schemas.

_Example 3: The following diagram depicts a denormalized schema for the simple model._

```
Sale
```
```
Sales
```
```
ID: Edm.String {id}
Amount: Edm.Decimal
```
```
Category
```
```
CategoryID: Edm.String
CategoryName: Edm.String
```
```
Product
```
```
ProductID: Edm.String
ProductName: Edm.String
ProductColor: Edm.String
ProductTaxRate: Edm.Decimal
Food FoodProductRating: Edm.Byte
Non-Food NonFoodProductRatingClass: Edm.String
```
```
Sales Organization
```
```
SalesOrganizationID: Edm.String
SalesOrganizationName: Edm.String
SalesOrganizationSuperordinateID: Edm.String
```
```
Time
```
```
TimeDate: Edm.Date
TimeMonth: Edm.String
TimeQuarter: Edm.String
TimeYear: Edm.Int
```
```
Customer
```
```
CustomerID: Edm.String
CustomerName: Edm.String
CustomerCountry: Edm.String
```

**2.2 Example Data**

_Example 4: The following entity sets and sample data will be used to further illustrate the capabilities introduced by this extension._

**2.3 Example Use Cases**

_Example 5: In the example model, one prominent use case is the relation of customers to products. The first question that is likely to be asked
is: “Which customers bought which products?”_

_This leads to the second more quantitative question: “Who bought how much of what?”_

_The answer to the second question typically is visualized as a cross-table:_

```
Food Non-Food
Sugar Coffee Paper
USA 14 2 12 5 5
Joe 6 2 4 1 1
Sue 8 8 4 4
Netherlands 2 2 3 3
Sue 2 2 3 3
```
```
Products
```
```
ID Category Name Color TaxRate
P1 PG1 Sugar White 0.
P2 PG1 Coffee Brown 0.
P3 PG2 Paper White 0.
P4 PG2 Pencil Black 0.
```
```
Food
```
```
Rating
5
```
```
n/a
n/a
```
```
Non-Food
```
```
RatingClass
n/a
n/a
average
```
Time

```
Date Month Quarter Year
2022-01-01 2022-01 2022-1 2022
2022-04-01 2022-04 2022-2 2022
2022-04-10 2022-04 2022-2 2022
...
```
```
Categories
```
```
ID Name
PG1 Food
PG2 Non-Food
```
```
Sales Organizations
```
```
ID Superordinate Name
Sales Corporate Sales
US Sales US
US West US US West
US East US US East
EMEA Sales EMEA
EMEA Central EMEA EMEA Central
```
Customers

```
ID Name Country
C1 Joe USA
C2 Sue USA
C3 Sue Netherlands
C4 Luc France
```
Sales

```
ID Customer Time Product Sales Organization Amount
1 C1 2022-01-03 P3 US West 1
2 C1 2022-04-10 P1 US West 2
3 C1 2022-08-07 P2 US West 4
4 C2 2022-01-03 P2 US East 8
5 C2 2022-11-09 P3 US East 4
6 C3 2022-04-01 P1 EMEA Central 2
7 C3 2022-08-06 P3 EMEA Central 1
8 C3 2022-11-22 P3 EMEA Central 2
```
```
Legend
```
```
Property
Key
Navigation Property
```

## The data in this cross-table can be written down in a shape that more closely resembles the structure of the data model, leaving cells empty that

_have been aggregated away:_

- USA Joe Non-Food Paper Customer/Country Customer/Name Product/Category/Name Product/Name Amount
- USA Joe Food Sugar
- USA Joe Food Coffee
- USA Sue Food Coffee
- USA Sue Non-Food Paper
- Netherlands Sue Food Sugar
- Netherlands Sue Non-Food Paper
- USA Food Sugar
- USA Food Coffee
- USA Non-Food Paper
- Netherlands Food Sugar
- Netherlands Non-Food Paper
- USA Joe Food
- USA Joe Non-Food
- USA Sue Food
- USA Sue Non-Food
- Netherlands Sue Food
- Netherlands Sue Non-Food
- USA Food
- USA Non-Food
- Netherlands Food
- Netherlands Non-Food


# 3 System Query Option $apply

A _set transformation_ ( _transformation_ for short) is an operation on an input set that produces an output set. A
_transformation sequence_ is a sequence of set transformations, separated by forward slashes to express that they are

consecutively applied. A transformation sequence may be invoked using the system query option **$apply**. The input set

of the first set transformation is the collection addressed by the resource path. The output set of each set transformation

is the input set for the next set transformation. The output set of the last set transformation in the transformation
sequence invoked by the system query option **$apply** is the result of **$apply**. This is consistent with the use of

service-defined bound and composable functions in path segments. Set transformations may also appear as a
parameter of certain other set transformations defined below.

The system query option **$apply** MUST NOT be used if the resource path addresses a single instance.

The system query option **$apply** is evaluated first, then the other system query options are evaluated, if applicable, on

the result of **$apply** , see **[OData-Protocol, section 11.2.1]**. Stability across requests for system query options **$top**

and **$skip [OData-Protocol, section 11.2.6.3]** is defined in section 3.3.7.

Each set transformation:

```
carries over the input type to the output set such that it fits into the data model of the service.
can mark certain navigation properties and stream properties for expansion by default , that is, they are expanded
in the result of $apply in the absence of an $expand query option.
may produce an output set with a different number of instances than the input set.
does not necessarily guarantee that all properties of the instances in the output set have a well-defined value.
```
Instances of an output set can contain structural and navigation properties, which can be declared or dynamic, as well
as instance annotations.

The allowed set transformations are defined in this section as well as in the section on Hierarchical Transformations.

Service-defined bound functions that take a collection of instances of a structured type as their binding parameter and

return a collection of instances of a structured type MAY be used as set transformations within **$apply**. Further

transformations can follow the bound function. The parameter syntax for bound function segments is identical to the
parameter syntax for bound functions in resource path segments or **$filter** expressions. See section 7.7 for an

example.

Parameter aliases **[OData-URL, section 5.3]** can be used inside the value of **$apply** wherever the ABNF rule

**applyTrafo [OData-ABNF]** is reduced to a **commonExpr [OData-URL, section 5.1.1]** or a **collectionExpr**

(section 3.6).

If a data service that supports **$apply** does not support it on the collection identified by the request resource path, it

MUST fail with **501 Not Implemented** and a meaningful human-readable error message.

On resource paths ending in **/$count** the system query option **$apply** is evaluated on the set identified by the

resource path without the **/$count** segment, the result is the plain-text number of items in the result of **$apply**. This is

similar to the combination of **/$count** and **$filter**.

During serialization of the result of **$apply** declared properties and dynamic properties are represented as defined by

the response format. Other properties have been aggregated away and are not represented in the response. The
entities returned in the request examples in the following sections that involve aggregation are therefore transient.

## 3.1 Fundamentals of Input and Output Sets

The definitions of italicized terms made in this section are used throughout this text, always with a hyperlink to this

section.


**3.1.1 Type, Structure and Context URL**

All input sets and output sets in one transformation sequence are collections of the _input type_ , that is the entity type or

complex type of the first input set, or in other words, of the resource to which the transformation sequence is applied.
The input type is determined by the entity model element identified within the metadata document by the context URL of
that resource **[OData-Protocol, section 10]**. Individual instances in an input or output set can have a subtype of the

input type. (See example 65.) The transformation sequence given as the **$apply** system query option is applied to the

resource addressed by the resource path. The transformations defined below can have nested transformation
sequences as parameters, these are then applied to resources that can differ from the current input set.

The _structure_ of an instance that occurs in an input or output set is defined by the names of the structural and navigation
properties that the instance contains. Instances of an input type can have different structures, subject to the following
rules:

```
Declared properties of the input type or a nested or related type thereof or of a subtype of one of these MUST
have their declared type and meaning when they occur in an input or output set.
Single- or collection-valued primitive properties addressed by a property path starting at a non-transient entity
MUST keep their values from the addressed resource path collection throughout the transformation sequence.
Likewise, single- or collection-valued navigation property paths starting at a non-transient entity MUST keep
addressing the same non-transient entities as in the addressed resource path collection.
Instances in an output set need not have all declared or dynamic properties that occurred in the input set.
Instances in an output set can have dynamic properties that did not occur in the input set. The name for such a
dynamic property is called an alias , it is a simple identifier (see [OData-CSDL, section 15.2] ). Aliases MUST differ
from names of declared properties in the input type, from names of properties in the first input set, and from names
of properties in the current input set. Aliases in one collection MUST also differ from each other.
Instances in an output set that have all key properties of an entity also have the metadata associated with that
entity, such as entity-id, read and edit URL (defined in [OData-Protocol, section 4] ) and ETag (defined in [OData-
Protocol, section 11.4.1.2] ) as well as relations to other entities [OData-Protocol, section 11.2.7].
```
Here is an overview of the structural changes made by different transformations:

```
During aggregation, many instances are replaced by one instance, properties that represent the aggregation level
are retained, and others are replaced by dynamic properties holding the aggregate value of the many instances or
a transformed copy of them.
During compute, dynamic properties are added to each instance.
During join, one instance with a collection of related instances is replaced by many copies, each of which is related
via a dynamic property to one of the related instances.
During concatenation, the same instances are transformed multiple times and the output sets with their potentially
different structures are concatenated.
```
An output set thus consists of instances with different structures. This is the same situation as with a collection of an
open type ( **[OData-CSDL, section 6.3]** and **[OData-CSDL, section 9.3]** ) and it is handled in the same way.

If the first input set is a collection of entities from a given entity set, then so are all input sets and output sets in the

transformation sequence. The **{select-list}** in the context URL **[OData-Protocol, section 10]** MUST describe only

properties that are present or annotated as absent (for example, if **Core.Permissions** is **None [OData-Protocol,**

**section 11.2.2]** ) in all instances of the collection, after applying any **$select** and **$expand** system query options. The

**{select-list}** SHOULD describe as many such properties as possible, even if the request involves a concatenation

that leads to a non-homogeneous structure. If the server cannot determine any such properties, the **{select-list}**

MUST consist of just the instance annotation **AnyStructure** defined in the **Core** vocabulary. (See example 66.)

**3.1.2 Sameness and Order**

Input sets and output sets are not sets of instances in the mathematical sense but collections, because the same

instance can occur multiple times in them. In other words: A collection contains values (which can be instances of

structured types or primitive values), possibly with repetitions. The occurrences of the values in the collection form a set


in the mathematical sense. The _cardinality_ of a collection is the total number of occurrences in it. When this text

describes a transformation algorithmically and stipulates that certain steps are carried out _for each occurrence_ in a
collection, this means that the steps are carried out multiple times for the same value if it occurs multiple times in the

collection.

A collection addressed by the resource path is returned by the service either as an ordered collection **[OData-Protocol,
section 11.4.9]** or as an unordered collection. The same applies to collections that are nested in or related to the
addressed resource as well as to collections that are the result of evaluating an expression starting with **$root** , which

occur, for example, as the first parameter of a hierarchical transformation.

But when such a collection is transformed by the **$apply** system query option, additional cases can arise that are

neither ordered nor totally unordered. For example, the **groupby** transformation retains any order within a group but not

between groups.

⚠ _Example 6: Request the top 10 sales per customer. The processing of the request can be parallelized per customer and the responses per
customer can be interleaved in the overall response. This means that for any given customer, their top 10 sales appear in the desired order,
though not consecutively._

```
GET /service/Sales?$apply=groupby((Customer),orderby(Amount desc)/top(10))
```
For every transformation defined in the following sections, it will be specified how it orders its output set, based on the

order of its input set. The order of the last output set can be further influenced by a **$orderby** system query option

before it is observed in the response payload.

An order of a collection is more precisely defined as follows: Given two different occurrences and in a collection,
which may be of the same value or of different values, precedes or precedes , but not both. It can be neither,
in which case the relative order of and does not matter. If precedes and precedes , then also
precedes , and never precedes. (This is a partial order in the mathematical sense defined on the set of

occurrences.)

When transformations are defined in the following sections, the algorithmic description sometimes contains an _order-
preserving loop_ over a collection. Such a loop processes the occurrences in an order chosen by the service in such a

way that is processed before whenever precedes. Likewise, in an _order-preserving sequence_ we
have whenever precedes.

A collection can be _stable-sorted_ by a list of expressions. In the stable-sorted collection an occurrence precedes if
and only if either

```
precedes according to the rules of [OData-Protocol, section 11.2.6.2] or
these rules do not determine a precedence in either direction between and but preceded in the
collection before the sort.
```
Stable-sorting of an ordered collection produces another ordered collection. A stable-sort does not necessarily produce

a total order, the sorted collection may still contain two occurrences whose relative order does not matter. The
transformation **orderby** performs a stable-sort.

The output set of a basic aggregation transformation can contain instances of an entity type without entity-id. After a

**concat** transformation, different occurrences of the same entity can differ in individual non-declared properties. To

account for such cases, the definition of sameness given in **[OData-URL, section 5.1.1.1.1]** is refined here. Instances of

structured types are _the same_ if

```
both are instances of complex types and both are null or both have the same structure and same values with null
considered different from absent or
both are instances of entity types without entity-id (see [OData-Protocol, section 4.3] ) and both are null or both
have the same structure and same values with null considered different from absent (informally speaking, they are
compared like complex instances) or
(1) both are instances of the same entity type with the same entity-id (non-transient entities, see [OData-Protocol,
section 4.1] ) and (2) the structural and navigation properties contained in both have the same values (for non-
```
```
u 1 u 2
u 1 u 2 u 2 u 1
u 1 u 2 u 1 u 2 u 2 u 3 u 1
u 3 u 1 u 1
```
```
u 1 u 2 u 1 u 2 u 1 ,..., un
i < j ui uj
```
```
u 1 u 2
```
```
u 1 u 2
u 1 u 2 u 1 u 2
```

```
primitive properties the sameness of values is decided by a recursive invocation of this definition).
If this is fulfilled, the instances are called complementary representations of the same non-transient entity. If
this case is encountered at some recursion level while the sameness of non-transient entities and is
established, a merged representation of the entity exists that contains all properties of and. But
if the instances both occur in the last output set, services MUST represent each with its own structure in the
response payload.
If the first condition is fulfilled but not the second, the instances are not the same and are called contradictory
representations of the same non-transient entity. (Example 84 describes a use case for this.)
```
Collections are _the same_ if there is a one-to-one correspondence between them such that

```
corresponding occurrences are of the same value and
an occurrence precedes another occurrence if and only if the occurrence precedes the occurrence
, where the occurrences and may be of the same value or of different values. (A one-to-one
correspondence with this second property is called order-preserving .)
```
**3.1.3 Evaluation of Data Aggregation Paths**

This document specifies how a data aggregation path that occurs in a request is evaluated by the service. If such an
evaluation fails, the service MUST reject the request.

For a data aggregation path to be a common expression according to **[OData-URL, section 5.1.1]** , its segments must
be single-valued with the possible exception of the last segment, and it can then be evaluated relative to an instance of

a structured type. For the transformations defined in this document, a data aggregation path can also be evaluated
relative to a collection , even if it has arbitrary collection-valued segments itself.

To this end, the following notation is used in the subsequent sections: If is a collection and a data aggregation path,
optionally followed by a type-cast segment, the result of such a path evaluation is denoted by and defined as the

unordered concatenation, possibly containing repetitions, of the collections for each in that is not null. The

function takes a non-null value and a path as arguments and returns a collection of instances of structured types

or primitive values, depending on the type of the final segment of. It is recursively defined as follows:

1. If is an empty path, let be a collection with as its single member and continue with step 9.
2. Let be the first segment of and the remainder, if any, such that equals the concatenated path.
3. If is a type-cast segment and is of its type or a subtype thereof, let and continue with step 8.
4. If is a type-cast segment and is not of its type or a subtype thereof, let be an empty collection and continue
    with step 9. (This rule follows **[OData-URL, section 4.1 1 ]** rather than **[OData-CSDL, section 14.4.1.1]** .)
5. Otherwise, is a non-type-cast segment. If does not contain a structural or navigation property , let be an
    empty collection and continue with step 9.
6. If is single-valued, let be the value of the structural or navigation property in. If is null, let be an empty
    collection and continue with step 9; otherwise continue with step 8.
7. Otherwise, is collection-valued. Let be the collection addressed by the structural or navigation property in ,
    and let. Then continue with step 9.
8. Let.
9. Return.

This notation is extended to the case of an empty path by setting with null values removed. Note the

collections returned by and never contain the null value. Also, every instance in occurs also in or nested

into , therefore an algorithmic step like “Add a dynamic property to each in ” effectively changes.

```
u 1 u 2
u 1 = u 2 u 1 u 2
```
```
f
```
```
u 1 u 2 f ( u 1 )
f ( u 2 ) u 1 u 2
```
```
A
```
```
A p
Γ( A , p )
γ ( u , p ) u A
γ ( u , p )
p
```
```
p B u
p 1 p p 2 p p 1 / p 2
p 1 u v = u
p 1 u B
```
```
p 1 u p 1 B
```
```
p 1 v p 1 u v B
```
```
p 1 C p 1 u
B =Γ( C , p 2 )
B = γ ( v , p 2 )
B
```
```
e Γ( A , e )= A
Γ γ u Γ( A , p ) A
A u Γ( A , p ) A
```

**3.2 Basic Aggregation**

**3.2.1 Transformation aggregate**

**3.2.1.1 Aggregation Algorithm**

The **aggregate** transformation takes a comma-separated list of one or more _aggregate expressions_ as parameters and

returns an output set with a single instance of the input type without entity-id containing one property per aggregate
expression, representing the aggregated value of the input set.

An aggregate expression MUST have one of the types listed below. To compute the value of the property for a given
aggregate expression, the **aggregate** transformation first determines a collection of instances of structured types or

primitive values, based on the input set of the **aggregate** transformation, and a path that occurs in the aggregate

expression. Let denote a data aggregation path with single- or collection-valued segments and a type-cast
segment. Depending on its type, the aggregate expression contains a path or or. Each type of

aggregate expression defines a function which the aggregate transformation evaluates to obtain the property

value.

The property is a dynamic property, except for a special case in type 4. In types 1 and 2, the aggregate expression
MUST end with the keyword **with** and an aggregation method. The aggregation method also determines the type of

the dynamic property. In types 1, 2, and 3 the aggregate expression MUST, and in type 4 it MAY, be followed by the
keyword **as** and an alias, which is then the name of the dynamic property.

_Types of aggregate expressions:_

1. A path or where the last segment of has a complex or entity or aggregatable primitive type
    whose values can be aggregated using the specified aggregation method , or if the input set can be
    aggregated using the custom aggregation method.
    Let.
2. An aggregatable expression whose values can be aggregated using the specified aggregation method.
    Let where is the collection consisting of the values of the aggregatable expression evaluated
    relative to each occurrence in with null values removed from. In this type, is absent.
3. A path (see section 3.2.1.4) with optional prefix where or or.
    Let be the cardinality of.
4. A path consisting of an optional prefix with or where the last segment of has a structured
    type or , and a custom aggregate defined on the collection addressed by.
    Let. If computation of the custom aggregate fails, the service MUST reject the request. In the absence
    of an alias:
       The name of the property is the name of the custom aggregate.
       The property is a dynamic property whose type is determined by the custom aggregate, unless there is a
       declared property with that name. The latter case is allowed by the **CustomAggregate** annotation.

_Determination of :_

Let be the input set. If is absent, let with null values removed.

Otherwise, let be the portion of up to and including the last navigation property, if any, and any type-cast segment

that immediately follows, and let be the remainder, if any, of that contains no navigation properties, such that equals
the concatenated path. The aggregate transformation considers each entity reached via the path exactly once. To

this end, using the notation:

```
If is non-empty, let and remove duplicates from that entity collection: If multiple representations of the
same non-transient entity are reached, the service MUST merge them into one occurrence in if they are
complementary and MUST reject the request if they are contradictory. If multiple occurrences of the same transient
entity are reached, the service MUST keep only one occurrence in.
If is empty, let.
```
```
A
p
p 1 p 2
p = p 1 p = p 2 p = p 1 / p 2
f ( A )
```
```
g
```
```
p = p 1 p = p 1 / p 2 p 1
g p = p 2
g
f ( A )= g ( A )
g
f ( A )= g ( B ) B
A B p
p /$count p / p = p 1 p = p 2 p = p 1 / p 2
f ( A ) A
p / c p / p = p 1 p = p 1 / p 2 p 1
p = p 2 c p
f ( A )= c ( A )
```
```
A
```
```
I p A = I
```
```
q p
r p p
q / r q
Γ
```
```
q E =Γ( I , q )
E
```
```
E
q E = I
```

Then, if is empty, let , otherwise let , this consists of instances of structured types or primitive values,

possibly with repetitions.

**3.2.1.2 Keyword as**

Aggregate expressions can be followed by the **as** keyword followed by an alias.

_Example 7:_

```
GET /service/Sales?$apply=aggregate(Amount with sum as Total,
Amount with max as MxA)
```
_results in_

_Example 8 :_

```
GET /service/Sales?$apply=aggregate(Amount mul Product/TaxRate
with sum as Tax)
```
_results in_

An alias affects the structure of the output set: each alias corresponds to a dynamic property in a **$select** option.

**3.2.1.3 Aggregation Methods**

Values can be aggregated using the standard aggregation methods **sum** , **min** , **max** , **average** , and **countdistinct** , or

with custom aggregation methods defined by the service. Only types 1 and 2 of the aggregation algorithm involve

aggregation methods, and the algorithm ensures that no null values occur among the values to be aggregated.

**3.2.1.3.1 Standard Aggregation Method sum**

The standard aggregation method **sum** can be applied to numeric values to return the sum of the values, or null if there

are no values to be aggregated. The provider MUST choose a single type for the property across all instances of that
type in the result that is capable of representing the aggregated values. This may require a larger integer type,
**Edm.Decimal** with sufficient **Precision** and **Scale** , or **Edm.Double**.

_Example 9:_

```
GET /service/Sales?$apply=aggregate(Amount with sum as Total)
```
_results in_

```
r A = E A =Γ( E , r )
```
### {

```
"@context": "$metadata#Sales(Total, MxA)",
"value": [
{ "Total@type": "Decimal", "Total": 24 ,
"MxA@type": "Decimal", "MxA": 8 }
]
}
```
### {

```
"@context": "$metadata#Sales(Tax)",
"value": [
{ "Tax@type": "Decimal", "Tax": 2.08 }
]
}
```
### {

```
"@context": "$metadata#Sales(Total)",
"value": [
{ "Total@type": "Decimal", "Total": 24 }
```

**3.2.1.3.2 Standard Aggregation Method min**

The standard aggregation method **min** can be applied to values with a totally ordered domain to return the smallest of

the values, or null if there are no values to be aggregated.

The result property will have the same type as the input property.

_Example 10:_

```
GET /service/Sales?$apply=aggregate(Amount with min as MinAmount)
```
_results in_

**3.2.1.3.3 Standard Aggregation Method max**

The standard aggregation method **max** can be applied to values with a totally ordered domain to return the largest of the

values, or null if there are no values to be aggregated.

The result property will have the same type as the input property.

_Example 11:_

```
GET /service/Sales?$apply=aggregate(Amount with max as MaxAmount)
```
_results in_

**3.2.1.3.4 Standard Aggregation Method average**

The standard aggregation method **average** can be applied to numeric values to return the sum of the values divided by

the count of the values, or null if there are no values to be aggregated.

The provider MUST choose a single type for the property across all instances of that type in the result that is capable of
representing the aggregated values; either **Edm.Double** or **Edm.Decimal** with sufficient **Precision** and **Scale**.

_Example 12:_

```
GET /service/Sales?$apply=aggregate(Amount with average as AverageAmount)
```
_results in_

### ]

### }

### {

```
"@context": "$metadata#Sales(MinAmount)",
"value": [
{ "MinAmount@type": "Decimal", "MinAmount": 1 }
]
}
```
### {

```
"@context": "$metadata#Sales(MaxAmount)",
"value": [
{ "MaxAmount@type": "Decimal", "MaxAmount": 8 }
]
}
```
### {

```
"@context": "$metadata#Sales(AverageAmount)",
"value": [
{ "AverageAmount@type": "Decimal", "AverageAmount": 3.0 }
```

**3.2.1.3.5 Standard Aggregation Method countdistinct**

The aggregation method **countdistinct** can be applied to arbitrary collections to count the distinct values. Instance

comparison uses the definition of equality in **[OData-URL, section 5.1.1.1.1]**.

The result property MUST have type **Edm.Decimal** with **Scale** 0 and sufficient **Precision**.

_Example 13:_

```
GET /service/Sales?$apply=aggregate(Product with countdistinct
as DistinctProducts)
```
_results in_

The number of instances in the input set can be counted with the aggregate expression **$count**.

**3.2.1.3.6 Custom Aggregation Methods**

Services can define custom aggregation methods if the functionality offered by the standard aggregation methods is not
sufficient for the intended consumers.

Custom aggregation methods MUST use a namespace-qualified name (see **[OData-ABNF]** ), i.e. contain at least one
dot. Dot-less names are reserved for future versions of this specification.

⚠ _Example 14: custom aggregation method that concatenates distinct string values separated by commas_

```
GET /service/Sales?$apply=groupby((Customer/Country),
aggregate(Amount with sum as Total,
Product/Name with Custom.concat as ProductNames))
```
_results in_

**3.2.1.4 Aggregate Expression $count**

The aggregate expression **$count** is defined as type 3 in the aggregation algorithm. It MUST always specify an alias

and MUST NOT specify an aggregation method.

The result property MUST have type **Edm.Decimal** with **Scale** 0 and sufficient **Precision**.

_Example 15:_

### ]

### }

### {

```
"@context": "$metadata#Sales(DistinctProducts)",
"value": [
{ "DistinctProducts@type": "Decimal", "DistinctProducts": 3 }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Total,ProductNames)",
"value": [
{ "Customer": { "Country": "Netherlands" },
"Total@type": "Decimal", "Total": 5 ,
"ProductNames": "Paper,Sugar" },
{ "Customer": { "Country": "USA" },
"Total@type": "Decimal", "Total": 19 ,
"ProductNames": "Coffee,Paper,Sugar" }
]
}
```

```
GET /service/Sales?$apply=aggregate($count as SalesCount)
```
_results in_

**3.2.2 Transformation concat**

The **concat** transformation takes two or more parameters, each of which is a sequence of set transformations.

It applies each transformation sequence to the input set and concatenates the intermediate output sets in the order of

the parameters into the output set, preserving the ordering of the individual output sets as well as the structure of each
instance in these sets, potentially leading to a non-homogeneously structured output set. If different intermediate output

sets contain dynamic properties with the same alias, clients SHOULD ensure they have the same type and meaning in
each intermediate output set.

⚠ _Example 16:_

```
GET /service/Sales?$apply=concat(topcount(2,Amount),
aggregate(Amount))
```
_results in_

_Note that two Sales entities with the second highest amount 4 exist in the input set; the entity with_ **ID** _3 is included in the result, because the
service chose to use the_ **ID** _property for imposing a stable ordering._

**3.2.3 Transformation groupby**

The **groupby** transformation takes one or two parameters where the second is a list of set transformations, separated

by forward slashes to express that they are consecutively applied. If the second parameter is not specified, it defaults to

a single transformation whose output set consists of a single instance of the input type without properties and without
entity-id.

The **groupby** transformation partitions the input set by the values of certain “grouping properties” and applies the given

set transformations to each partition, this is called “simple grouping”.

**3.2.3.1 Simple Grouping**

The first parameter of **groupby** specifies the _grouping properties_ , a comma-separated parenthesized list of one or

more data aggregation paths with single-valued segments. The same path SHOULD NOT appear more than once;
redundant property paths MAY be considered valid, but MUST NOT alter the meaning of the request. Navigation

properties and stream properties specified in grouping properties are expanded by default (see example 63).

The algorithmic description of this transformation makes use of the following definitions: Let denote the value of a

structural or navigation property in an instance. A path is called a _prefix_ of a path if there is a non-empty path

such that equals the concatenated path. Let denote the empty path.

### {

```
"@context": "$metadata#Sales(SalesCount)",
"value": [
{ "SalesCount@type": "Decimal", "SalesCount": 8 }
]
}
```
### {

```
"@context": "$metadata#Sales(Amount)",
"value": [
{ "ID": 4 , "Amount": 8 },
{ "ID": 3 , "Amount": 4 },
{ "Amount": 24 }
]
}
```
```
G
```
```
u [ q ]
q u p 1 p p 2
p p 1 / p 2 e
```

The output set of the **groupby** transformation is constructed in five steps.

1. For each occurrence in the input set, a projection is computed that contains only the grouping properties. This
    projection is and the function takes an instance and a path relative to the input set as arguments
    and is computed recursively as follows:
       Let be an instance of the type of without properties and without entity-id.
       For each structural or navigation property of :
          If has a subtype of the type addressed by and is only declared on that subtype, let
          where is a type-cast to the subtype, otherwise let.
          If occurs in , let.
          Otherwise, if is a prefix of a path in and has a structured type, let.
       Return.
2. The input set is split into subsets where two instances are in the same subset if their projections are the same. If
    representations of the same non-transient entity are encountered during the comparison of two projections, the
    service MUST assign them to one subset with the merged representation if they are complementary and MUST
    reject the request if they are contradictory.
3. The set transformations from the second parameter are applied to each subset, resulting in a new set of potentially
    different structure and cardinality. Associated with each resulting set is the common projection of the instances in
    the subset from which the resulting set was computed.
4. Each set resulting from the previous step is transformed to contain the associated common projection. This
    transformation is denoted by and is defined below.
5. The output set is the concatenation of the transformed sets from the previous step. The order of occurrences from
    the same transformed set remains the same, and no order is defined between occurrences from different
    transformed sets.

_Definition of :_

_Prerequisites:_ is a list of data aggregation paths and is an instance of the input type.

The output set of the transformation is in one-to-one correspondence with its input set via the order-preserving

mapping. The function takes two instances and a path relative to the input set as arguments

and is computed recursively as follows:

1. If necessary, cast to a subtype so that its type contains all structural and navigation properties of.
2. For each structural or navigation property of :
    If has a subtype of the type addressed by and is only declared on that subtype, let where
    is a type-cast to the subtype, otherwise let.
    If is a single-valued primitive structural property or occurs in , let. (In the case where
    occurs in we also call a _final segment from_ .)
    Otherwise, if is single-valued, let.
    Otherwise, the behavior is undefined. (Such cases never occur when is used in this document.)
3. Return.

_Example 17:_

```
GET /service/Sales?$apply=groupby((Customer/Country,Product/Name),
aggregate(Amount with sum as Total))
```
_results in_

```
u
sG ( u , e ) sG ( u , p )
```
```
v u
q u
u p q p ′= p / p ′′/ q
p ′′ p ′= p / q
p ′ G v [ q ]= u [ q ]
p ′ G u [ q ] v [ q ]= sG ( u [ q ], p ′)
v
```
```
s
Π G ( s )
```
```
Π G ( s )
```
```
G s
```
```
Π G ( s )
u ↦ aG ( u , s , e ) aG ( u , s , p )
```
```
u s
q s
s p q p ′= p / p ′′/ q p ′′
p ′= p / q
q p ′ G u [ q ]= s [ q ] p ′
G q G
q u [ q ]= aG ( u [ q ], s [ q ], p ′)
Π G ( s )
u
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Product(Name),Total)",
"value": [
{ "Customer": { "Country": "Netherlands" },
"Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 3 },
```

If the second parameter is omitted, steps 2 and 3 above produce one instance containing only the grouping properties
per distinct value combination.

⚠ _Example 18:_

```
GET /service/Sales?$apply=groupby((Product/Name,Amount))
```
_results in_

_Note that the result has the same structure, but not the same content as_

```
GET /service/Sales?$expand=Product($select=Name)&$select=Amount
```
A **groupby** transformation affects the structure of the output set similar to **$select** where each grouping property

corresponds to an item in a **$select** clause.

**3.3 Transformations Producing a Subset**

These transformations produce an output set that is a subset of their input set, possibly in a different order. Some of the

algorithmic descriptions below make use of the following definition: A total order of a collection is called _stable across
requests_ if it is the same for all requests that construct the collection by executing the same resource path and
transformations, possibly nested, on the same underlying data.

⚠ _Example 19: A stable total order is required for the input set of a_ **skip** _transformation. The following request constructs that input set by
executing the_ **groupby** _transformation on the_ **Sales** _entity collection, computing the total sales per customer. Because of the subsequent_ **skip**
_transformation, the service must endow this with a stable total order. Then the request divides the total sales per customer into pages of
customers and returns page number in a reproducible manner (as long as the underlying data do not change)._

```
GET /service/Sales?$apply=
groupby((Customer),aggregate(Amount with sum as Total))
/skip(M)/top(N)
```
_where the number in_ **skip** _is. Other values of can be used to skip, for example, half a page._

```
{ "Customer": { "Country": "Netherlands" },
"Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 2 },
{ "Customer": { "Country": "USA" },
"Product": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12 },
{ "Customer": { "Country": "USA" },
"Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 5 },
{ "Customer": { "Country": "USA" },
"Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 2 }
]
}
```
### {

```
"@context": "$metadata#Sales(Product(Name),Amount)",
"value": [
{ "Product": { "Name": "Coffee" }, "Amount": 4 },
{ "Product": { "Name": "Coffee" }, "Amount": 8 },
{ "Product": { "Name": "Paper" }, "Amount": 1 },
{ "Product": { "Name": "Paper" }, "Amount": 2 },
{ "Product": { "Name": "Paper" }, "Amount": 4 },
{ "Product": { "Name": "Sugar" }, "Amount": 2 }
]
}
```
### N

```
i
```
```
M =( i − 1 )⋅ N M
```

**3.3.1 Top/bottom transformations**

These transformations take two parameters. The first parameter MUST be an expression that is evaluable on the input

set as a collection, without reference to an individual instance (and which therefore cannot be a property path). The
second parameter MUST be an expression that is evaluated on each instance of the input set in turn.

The output set is constructed as follows:

1. Let be a copy of the input set with a total order that is chosen by the service (it need not preserve any existing
    order). The total order MUST be stable across requests. (This is the order of the eventual output set of this
    transformation.)
2. Let be a copy of that is stable-sorted in ascending (for transformations starting with **bottom** ) or descending
    (for transformations starting with **top** ) order of the value specified in the second parameter. (This is the order in
    which contributions to the output set are considered.)
3. Start with an empty output set.
4. Loop over in its total order.
5. Exit the loop if a condition is met. This condition depends on the transformation being executed and is given in the
    subsections below.
6. Insert the current item of the loop into the output set in the order of.
7. Continue the loop.

For example, if the input set consists of non-transient entities and the datastore contains an index ordered by the
second parameter and then the entity-id, a service may implement this algorithm with ordered like this index.

The order of the output set can be influenced with a subsequent **orderby** transformation.

**3.3.1.1 Transformations bottomcount and topcount**

The first parameter MUST evaluate to a positive integer. The second parameter MUST evaluate to a primitive type

whose values are totally ordered. In step 5, exit the loop if the cardinality of the output set equals.

_Example 20:_

```
GET /service/Sales?$apply=bottomcount(2,Amount)
```
_results in_

_Example 21:_

```
GET /service/Sales?$apply=topcount(2,Amount)
```
_results in_

```
A
```
```
B A
```
```
B
```
```
A
```
```
A = B
```
```
c
c
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 1 , "Amount": 1 },
{ "ID": 7 , "Amount": 1 }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 }
]
}
```

_Note that two_ **Sales** _entities with the second highest amount 4 exist in the input set; the entity with_ **ID** _3 is included in the result, because the
service chose to use the_ **ID** _property for imposing a stable ordering in step 1. Such a logic needs to be in place even with a preceding_ **orderby**
_since it cannot be ensured that it creates a stable order of the instances on the expressions of the second parameter._

**3.3.1.2 Transformations bottompercent and toppercent**

The first parameter MUST evaluate to a positive number less than or equal to 100. The second parameter MUST
evaluate to a number. In step 5, exit the loop if the ratio of the sum of the numbers addressed by the second parameter
in the output set to their sum in the input set equals or exceeds percent.

_Example 22:_

```
GET /service/Sales?$apply=bottompercent(50,Amount)
```
_results in_

_Example 23:_

```
GET /service/Sales?$apply=toppercent(50,Amount)
```
_results in_

**3.3.1.3 Transformations bottomsum and topsum**

The first parameter MUST evaluate to a number. The second parameter MUST be an aggregatable expression that
evaluates to a number. In step 5, exit the loop if the sum of the numbers addressed by the second parameter in the
output set is greater than or equal to.

_Example 24:_

```
GET /service/Sales?$apply=bottomsum(7,Amount)
```
_results in_

```
p
```
```
p
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 1 , "Amount": 1 },
{ "ID": 2 , "Amount": 2 },
{ "ID": 5 , "Amount": 4 },
{ "ID": 6 , "Amount": 2 },
{ "ID": 7 , "Amount": 1 },
{ "ID": 8 , "Amount": 2 }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 }
]
}
```
```
s
```
```
s
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 1 , "Amount": 1 },
{ "ID": 2 , "Amount": 2 },
{ "ID": 6 , "Amount": 2 },
{ "ID": 7 , "Amount": 1 },
{ "ID": 8 , "Amount": 2 }
```

_Example 25:_

```
GET /service/Sales?$apply=topsum(15,Amount)
```
_results in_

**3.3.2 Transformation filter**

The **filter** transformation takes a Boolean expression that could also be passed as a **$filter** system query option.

Its output set is the subset of the input set containing all instances (possibly with repetitions) for which this expression,

evaluated relative to the instance, yields true. No order is defined on the output set.

_Example 26:_

```
GET /service/Sales?$apply=filter(Amount gt 3)
```
_results in_

**3.3.3 Transformation orderby**

The **orderby** transformation takes a list of expressions that could also be passed as a **$orderby** system query option.

Its output set consists of the instances of the input set in the same order **$orderby** would produce for the given

expressions, but keeping the relative order from the input set if the given expressions do not distinguish between two

instances. The orderby transformation thereby performs a stable-sort. A service supporting this transformation MUST at
least offer sorting by values addressed by property paths, including dynamic properties, with both suffixes **asc** and

**desc**.

_Example 27:_

```
GET /service/Sales?$apply=groupby((Product/Name),
aggregate(Amount with sum as Total))
/orderby(Total desc)
```
_results in_

### ]

### }

### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 },
{ "ID": 5 , "Amount": 4 }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 },
{ "ID": 5 , "Amount": 4 }
]
}
```
### {

```
"@context": "$metadata#Sales(Product(Name),Total)",
"value": [
{ "Product": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12 },
{ "Product": { "Name": "Paper" },
```

**3.3.4 Transformation search**

The **search** transformation takes a search expression that could also be passed as a **$search** system query option.

Its output set is the subset of the input set containing all instances (possibly with repetitions) that match this search
expression. Closing parentheses in search expressions must be within single or double quotes in order to avoid syntax
errors like **search())**. No order is defined on the output set.

_Example 28: assuming that free-text search on_ **Sales** _takes the related product name into account,_

```
GET /service/Sales?$apply=search(coffee)
```
_results in_

**3.3.5 Transformation skip**

The **skip** transformation takes a non-negative integer as argument. Let be a copy of the input set with a total order

that extends any existing order of the input set but is otherwise chosen by the service. The total order MUST be stable
across requests.

The transformation excludes from the output set the first occurrences in. It keeps all remaining instances in the
same order as they occur in.

_Example 29:_

```
GET /service/Sales?$apply=orderby(Customer/Name desc)/skip(2)/top(2)
```
_results in_

**3.3.6 Transformation top**

The **top** transformation takes a non-negative integer as argument. Let be a copy of the input set with a total order

that extends any existing order of the input set but is otherwise chosen by the service. The total order MUST be stable
across requests.

If contains more than instances, the output set consists of the first occurrences in. Otherwise, the output set

equals. The instances in the output set are in the same order as they occur in.

Note the transformation **top(0)** produces an empty output set.

```
"Total@type": "Decimal", "Total": 8 },
{ "Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 4 }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 }
]
}
```
```
c A
```
```
c A
A
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 6 , "Amount": 2 },
{ "ID": 7 , "Amount": 1 }
]
}
```
```
c A
```
```
A c c A
A A
```

_Example 30:_

```
GET /service/Sales?$apply=orderby(Customer/Name desc)/top(2)
```
_results in_

**3.3.7 Stable Total Order Before $skip and $top**

When the system query options **$top** and **$skip [OData-Protocol, section 11.2.6.3]** are executed after the system

query option **$apply** and after **$filter** and **$orderby** , if applicable, they operate on a collection with a total order

that extends any existing order but is otherwise chosen by the service. The total order MUST be stable across requests.

**3.4 One-to-One Transformations**

These transformations produce an output set in one-to-one correspondence with their input set. The output set is initially
a clone of the input set, then dynamic properties are added to the output set. The values of properties copied from the
input set are not changed, nor is the order of instances changed.

**3.4.1 Transformation identity**

The output set of the **identity** transformation is its input set in unchanged order.

_Example 31: Add a grand total row to the_ **Sales** _result set_

```
GET /service/Sales?$apply=concat(identity,aggregate(Amount with sum as Total))
```
**3.4.2 Transformation compute**

The **compute** transformation takes a comma-separated list of one or more _compute expressions_ as parameters.

A compute expression is a common expression followed by the **as** keyword, followed by an alias.

The output set is constructed by copying the instances of the input set and adding one dynamic property per compute
expression to each occurrence in the output set. The name of each added dynamic property is the alias of the
corresponding compute expression. The value of each added dynamic property is computed relative to the
corresponding instance. Services MAY support expressions that address dynamic properties added by other

expressions within the same **compute** transformation, provided that the service can determine an evaluation sequence.

The type of the property is determined by the rules for evaluating common expressions and numeric promotion defined
in **[OData-URL, section 5.1.1]**.

_Example 32:_

```
GET /service/Sales?$apply=compute(Amount mul Product/TaxRate as Tax)
```
_results in_

### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 4 , "Amount": 8 },
{ "ID": 5 , "Amount": 4 }
]
}
```
### {

```
"@context": "$metadata#Sales(*,Tax)",
"value": [
{ "ID": 1 , "Amount": 1 , "Tax@type": "Decimal", "Tax": 0. 14 },
{ "ID": 2 , "Amount": 2 , "Tax@type": "Decimal", "Tax": 0. 12 },
{ "ID": 3 , "Amount": 4 , "Tax@type": "Decimal", "Tax": 0. 24 },
{ "ID": 4 , "Amount": 8 , "Tax@type": "Decimal", "Tax": 0. 48 },
```

**3.5 Transformations Changing the Input Set Structure**

The output set of the join transformations differs from their input set in the number of instances as well as in their
structure, but reflects the order of the input set.

**3.5.1 Transformations join and outerjoin**

The **join** and **outerjoin** transformations take as their first parameter a collection-valued complex or navigation

property, optionally followed by a type-cast segment to address only instances of that derived type or one of its sub-

types, followed by the **as** keyword, followed by an alias. The optional second parameter specifies a transformation

sequence.

For each occurrence in an order-preserving loop over the input set

1. the instance collection addressed by is identified.
2. If is provided, is replaced with the result of applying to.
3. In case of an **outerjoin** , if is empty, a null instance is added to it.
4. For each occurrence in an order-preserving loop over an instance is appended to the output set of the
    transformation:
       The instance is a clone of with an additional dynamic property whose name is the given alias and whose
       value is.
       The dynamic property is a navigation property if is a collection-valued navigation property, otherwise it is a
       complex property.
       The dynamic property carries as control information the context URL of.

_Example 33: all links between products and sales instances_

```
GET /service/Products?$apply=join(Sales as Sale)&$select=ID&$expand=Sale
```
_results in_

```
{ "ID": 5 , "Amount": 4 , "Tax@type": "Decimal", "Tax": 0. 56 },
{ "ID": 6 , "Amount": 2 , "Tax@type": "Decimal", "Tax": 0. 12 },
{ "ID": 7 , "Amount": 1 , "Tax@type": "Decimal", "Tax": 0. 14 },
{ "ID": 8 , "Amount": 2 , "Tax@type": "Decimal", "Tax": 0. 28 }
]
}
```
```
p
```
```
T
```
```
u
```
```
A p
T A T A
A
```
```
v A w
```
```
w u
v
p
```
```
v
```
### {

```
"@context": "$metadata#Products(ID,Sale())",
"value": [
{ "ID": "P1",
"Sale": {
"@context": "#Sales/$entity",
"ID": 2 , "Amount": 2 } },
{ "ID": "P1",
"Sale": {
"@context": "#Sales/$entity",
"ID": 6 , "Amount": 2 } },
{ "ID": "P2",
"Sale": {
"@context": "#Sales/$entity",
"ID": 3 , "Amount": 4 } },
{ "ID": "P2",
"Sale": {
"@context": "#Sales/$entity",
"ID": 4 , "Amount": 8 } },
{ "ID": "P3",
"Sale": {
"@context": "#Sales/$entity",
"ID": 1 , "Amount": 1 } },
```

_In this example,_ **$expand=Sale** _is used to include the target entities in the result. There are no subsequent transformations like_ **groupby** _that
would cause it to be expanded by default. If the first parameter_ **Sales** _was a collection-valued complex property of type_
**SalesModel.SalesComplexType** _, the complex property_ **Sale** _would be in the result regardless, and its context would be_ **"@context":
"#SalesModel.SalesComplexType"**_._

_Applying_ **outerjoin** _instead would return an additional instance for product with_ **"ID": "P4"** _and_ **Sale** _having a null value._

**3.6 Expressions Evaluable on a Collection**

The following two subsections introduce two new types of expression that are evaluated relative to a collection, called
the input collection.

These expressions are

```
either prepended with a collection-valued path followed by a forward slash, like a lambda operator [OData-URL,
section 5.1.1.13]. The collection identified by that path is then the input collection for the expression.
or prepended with the keyword $these followed by a forward slash, the input collection is then the current
collection defined as follows:
In a system query option other than $apply , possibly nested within $expand or $select , the current
collection is the collection that is the subject of the system query option.
In a path segment that addresses a subset of a collection [OData-URL, section 4.12] , the current collection
is the collection that is the subject of the path segment.
In an $apply transformation, the current collection is the input set of the transformation.
```
**3.6.1 Function aggregate**

The **aggregate** function allows the use of aggregated values in expressions. It takes a single parameter accepting an

aggregate expression and returns the aggregated value of type **Edm.PrimitiveType** as the result from applying the

aggregate expression on its input collection.

More precisely, if is an aggregate expression, the function or evaluates to the

value of the property in the single instance of the output set that is produced when the transformation

```
is applied with the input collection as input set.
```
_Example 34: Sales making up at least a third of the total sales amount._

```
GET /service/Sales?$filter=Amount mul 3 ge $these/aggregate(Amount with sum)
```
_results in_

### { "ID": "P3",

```
"Sale": {
"@context": "#Sales/$entity",
"ID": 5 , "Amount": 4 } },
{ "ID": "P3",
"Sale": {
"@context": "#Sales/$entity",
"ID": 7 , "Amount": 1 } },
{ "ID": "P3",
"Sale": {
"@context": "#Sales/$entity",
"ID": 8 , "Amount": 2 } }
]
}
```
```
p
```
```
α p /aggregate( α ) $these/aggregate( α )
D
```
aggregate( _α_ as _D_ )

### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": "4", "Amount": 8 }
```

_Example 35: Products with more than 1.00 sales tax. The aggregate expression of type 2 combines paths with and without_ **$it** _prefix (compare
this with example 8)._

```
GET /service/Products?$filter=Sales/aggregate(Amount mul $it/TaxRate with sum)
gt 1
```
⚠ _Example 36: products with a single sale of at least twice the average sales amount_

```
GET /service/Products?$filter=Sales/any(s:s/Amount ge
Sales/aggregate(Amount with average) mul 2)
```
_Both examples result in_

**3.6.2 Expression $count**

The expression **$count** evaluates to the cardinality of the input collection.

_Example 37 : The input collection for_ **$count** _consists of all sales entities, the top third of sales entities by amount form the result._

```
GET /service/Sales?$apply=topcount($these/$count div 3,Amount)
```
_results in 2 (a third of 8, rounded down) entities. (This differs from_ **toppercent(33.3,Amount)** _, which returns only the sales entity with_ **ID** _4,
because that already makes up a third of the total amount.)_

A definition that is equivalent to a **$count** expression after a collection-valued path was made in **[OData-URL, section**

**4.8]**.

**3.7 Function isdefined**

Properties that are not explicitly mentioned in **aggregate** or **groupby** are considered to have been _aggregated away_.

Since they are treated as having the null value in **$filter** expressions **[OData-URL, section 5.1.1.15]** , the **$filter**

expression **Product eq null** cannot distinguish between an instance containing the value for the null product and the

instance containing the aggregated value across all products (where the **Product** has been aggregated away).

The function **isdefined** can be used to determine whether a property is present or absent in an instance. It takes a

single-valued property path as its only parameter and returns true if the property is present in the instance for which the
expression containing the **isdefined** function call is evaluated. A present property can still have the null value; it can

represent a grouping of null values, or an aggregation that results in a null value.

_Example 38:_ **Product** _has been aggregated away, causing an empty result_

```
GET /service/Sales?$apply=aggregate(Amount with sum as Total)
&$filter=isdefined(Product)
```
### ]

### }

### {

```
"@context": "$metadata#Products",
"value": [
{ "ID": "P3", "Name": "Paper", "Color": "White", "TaxRate": 0. 14 }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": 3 , "Amount": 4 },
{ "ID": 4 , "Amount": 8 }
]
}
```

_results in_

**3.8 Evaluating $apply as an Expand and Select Option**

The new system query option **$apply** can be used as an expand or select option to inline the result of aggregating

related entities or nested instances. The rules for evaluating **$apply** are applied in the context of the related collection

of entities or the selected collection of instances, meaning this context defines the input set of the first transformation.

Furthermore, **$apply** is evaluated first, and other expand or select options on the same (navigation) property are

evaluated on the result of **$apply**.

_Example 39: products with aggregated sales_

```
GET /service/Products
?$expand=Sales($apply=aggregate(Amount with sum as Total))
```
_results in_

**3.9 ABNF for Extended URL Conventions**

The normative ABNF construction rules for this specification are defined in **[OData-Agg-ABNF]**. They incrementally
extend the rules defined in **[OData-ABNF]**.

### {

```
"@context": "$metadata#Sales(Total)",
"value": []
}
```
### {

```
"@context": "$metadata#Products(Sales(Total))",
"value": [
{ "ID": "P2", "Name": "Coffee", "Color": "Brown", "TaxRate": 0 .06,
"Sales": [ { "Total@type": "Decimal", "Total": 12 } ] },
{ "ID": "P3", "Name": "Paper", "Color": "White", "TaxRate": 0. 14 ,
"Sales": [ { "Total@type": "Decimal", "Total": 8 } ] },
{ "ID": "P4", "Name": "Pencil", "Color": "Black", "TaxRate": 0. 14 ,
"Sales": [ { "Total": null } ] },
{ "ID": "P1", "Name": "Sugar", "Color": "White", "TaxRate": 0 .06,
"Sales": [ { "Total@type": "Decimal", "Total": 4 } ] }
]
}
```

# 4 Cross-Joins and Aggregation

OData supports querying related entities through defining navigation properties in the data model. These navigation

paths help guide simple consumers in understanding and navigating relationships.

In some cases, however, requests need to span entity sets with no predefined associations. Such requests can be sent
to the special resource **$crossjoin** instead of an individual entity set. The cross join of a list of entity sets is the

Cartesian product of the listed entity sets, represented as a collection of complex type instances that have a navigation
property with cardinality to-one for each participating entity set, and queries across entity sets can be formulated using

these navigation properties. See **[OData-URL, section 4.15]** for details.

Where useful navigations exist it is beneficial to expose those as explicit navigation properties in the model, but the

ability to pose queries that span entity sets not related by an association provides a mechanism for advanced
consumers to use more flexible join conditions.

_Example 40: if_ **Sale** _had a string property_ **ProductID** _instead of the navigation property_ **Product** _, a “join” between_ **Sales** _and_ **Products**
_could be accessed via the_ **$crossjoin** _resource_

```
GET /service/$crossjoin(Products,Sales)
?$expand=Products($select=Name),Sales($select=Amount)
&$filter=Products/ID eq Sales/ProductID
```
_results in_

_Example 41: using the_ **$crossjoin** _resource for aggregate queries_

```
GET /service/$crossjoin(Products,Sales)
?$apply=filter(Products/ID eq Sales/ProductID)
/groupby((Products/Name),
aggregate(Sales/Amount with sum as Total))
```
_results in_

The entity container may be annotated in the same way as entity sets to express which aggregate queries are
supported, see section 5.

### {

```
"@context": "$metadata#Collection(Edm.ComplexType)",
"value": [
{ "Products": { "Name": "Paper" }, "Sales": { "Amount": 1 } },
{ "Products": { "Name": "Sugar" }, "Sales": { "Amount": 2 } },
...
]
}
```
### {

```
"@context": "$metadata#Collection(Edm.ComplexType)",
"value": [
{ "Products": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12 },
{ "Products": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 8 },
{ "Products": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 4 }
]
}
```

# 5 Vocabulary for Data Aggregation

The following terms are defined in the vocabulary for data aggregation **[OData-VocAggr]**.

## 5.1 Aggregation Capabilities

The term **ApplySupported** can be applied to an entity set, an entity type, or a collection if the target expression of the

annotation starts with an entity container (see example 43). It describes the aggregation capabilities of the annotated
target. If present, it implies that instances of the annotated target can contain dynamic properties as an effect of **$apply**

even if they do not specify the **OpenType** attribute, see **[OData-CSDL, section 6.3]**. The term has a complex type with

the following properties:

```
The Transformations collection lists all supported set transformations. Allowed values are the names of the
standard transformations introduced in sections 3 and 6, and namespace-qualified names identifying a service-
defined bindable function. If Transformations is omitted the server supports all transformations defined by this
specification.
The CustomAggregationMethods collection lists supported custom aggregation methods. Allowed values are
namespace-qualified names identifying service-specific aggregation methods. If omitted, no custom aggregation
methods are supported.
🚧 Rollup is reserved for later versions of this specifications. The functional scope of this version of the
specification is expressed by giving Rollup the value None.
A non-empty GroupableProperties indicates that only the listed properties of the annotated target can be used
in groupby.
A non-empty AggregatableProperties indicates that only the listed properties of the annotated target can be
used in aggregate , optionally restricted to the specified aggregation methods.
```
All properties of **ApplySupported** are optional, so it can be used as a tagging annotation to signal unlimited support of

aggregation.

The term **ApplySupportedDefaults** can be applied to an entity container. It allows to specify default support for

aggregation capabilities **Transformations** , **CustomAggregationMethods** and **Rollup** that propagate to all

collection-valued resources in the container. Annotating a specific collection-valued resource with the term
**ApplySupported** overrides the default support with the specified properties using **PATCH** semantics:

```
Primitive or collection-valued properties specified in ApplySupported replace the corresponding properties
specified in ApplySupportedDefaults.
Complex-valued properties specified in ApplySupported override the corresponding properties specified in
ApplySupportedDefaults using PATCH semantics recursively.
Properties specified neither in ApplySupported nor in ApplySupportedDefault have their default value.
```
_Example 42: an entity container with default support for everything defined in this specification_

_Example 43 : Define aggregation support only for the products of a given category_

```
< EntityContainer Name="SalesData">
< Annotation Term="Aggregation.ApplySupportedDefaults" />
...
</ EntityContainer >
```
```
< Annotations Target="SalesModel.SalesData/Categories/Products">
< Annotation Term="Aggregation.ApplySupported">
...
</ Annotation >
</ Annotations >
```

**5.2 Custom Aggregates**

The term **CustomAggregate** allows defining dynamic properties that can be used in **aggregate**. No assumptions can

be made on how the values of these custom aggregates are calculated, whether they are null, and which input values
are used.

When applied to an entity set, an entity type, or a collection if the target expression of the annotation starts with an entity
container, the annotation specifies custom aggregates that are available for its instances and for aggregated instances

resulting from these instances. When applied to an entity container, the annotation specifies custom aggregates whose

input set may span multiple entity sets within the container.

A custom aggregate is identified by the value of the **Qualifier** attribute when applying the term. The value of the

**Qualifier** attribute is the name of the dynamic property. The name MUST NOT collide with the names of other custom

aggregates of the same model element.

The value of the annotation is a string with the qualified name of a primitive type or type definition in scope that specifies
the type returned by the custom aggregate.

If the custom aggregate is associated with an entity set, entity type, or collection, the value of the **Qualifier** attribute

MAY be identical to the name of a declared property of the instances in this set or collection. In these cases, the value of
the annotation MUST have the same value as the **Type** attribute of the declared property. This is typically done when

the custom aggregate is used as a default aggregate for that property. In this case the name refers to the custom
aggregate within an aggregate expression without a **with** clause, and to the property in all other cases.

If the custom aggregate is associated with an entity container, the value of the **Qualifier** attribute MUST NOT collide

with the names of any entity container children.

_Example 44: Sales forecasts are modeled as a custom aggregate of the Sale entity type because it belongs there. For the budget, there is no
appropriate structured type, so it is modeled as a custom aggregate of the_ **SalesData** _entity container._

_These custom aggregates can be used in the_ **aggregate** _transformation:_

```
GET /service/Sales?$apply=groupby((Time/Month),aggregate(Forecast))
```
_and:_

```
GET /service/$crossjoin(Time)?$apply=groupby((Time/Year),aggregate(Budget))
```
**5.3 Context-Defining Properties**

Sometimes the value of a property or custom aggregate is only well-defined within the context given by values of other

properties, e.g. a postal code together with its country, or a monetary amount together with its currency unit. These
context-defining properties can be listed with the term **ContextDefiningProperties** whose type is a collection of

property paths.

If present, the context-defining properties SHOULD be used as grouping properties when aggregating the annotated
property or custom aggregate, or alternatively be restricted to a single value by a pre-filter operation. Services MAY

respond with **400 Bad Request** if the context-defining properties are not sufficiently specified for calculating a

meaningful aggregate value.

```
< Annotations Target="SalesModel.SalesData/Sales">
< Annotation Term="Aggregation.CustomAggregate" Qualifier="Forecast"
String="Edm.Decimal" />
</ Annotations >
< Annotations Target="SalesModel.SalesData">
< Annotation Term="Aggregation.CustomAggregate" Qualifier="Budget"
String="Edm.Decimal" />
</ Annotations >
```

**5.4 Annotation Example**

_Example 45: This simplified_ **Sales** _entity set has a single aggregatable property_ **Amount** _whose context is defined by the_ **Code** _property of the
related_ **Currency** _, and a custom aggregate_ **Forecast** _with the same context. The_ **Code** _property of_ **Currencies** _is groupable. All other
properties are neither groupable nor aggregatable._

```
< EntityType Name="Currency">
< Key >
< PropertyRef Name="Code" />
</ Key >
< Property Name="Code" Type="Edm.String" />
< Property Name="Name" Type="Edm.String">
< Annotation Term="Core.IsLanguageDependent" />
</ Property >
</ EntityType >
```
```
< EntityType Name="Sale">
< Key >
< PropertyRef Name="ID" />
</ Key >
< Property Name="ID" Type="Edm.String" Nullable="false" />
< Property Name="Amount" Type="Edm.Decimal" Scale="variable">
< Annotation Term="Aggregation.ContextDefiningProperties">
< Collection >
< PropertyPath >Currency/Code</ PropertyPath >
</ Collection >
</ Annotation >
</ Property >
< NavigationProperty Name="Currency" Type="SalesModel.Currency"
Nullable="false" />
</ EntityType >
```
```
< EntityContainer Name="SalesData">
< EntitySet Name="Sales" EntityType="SalesModel.Sale">
< Annotation Term="Aggregation.ApplySupported">
< Record >
< PropertyValue Property="AggregatableProperties">
< Collection >
< Record >
< PropertyValue Property="Property" PropertyPath="Amount" />
</ Record >
</ Collection >
</ PropertyValue >
< PropertyValue Property="GroupableProperties">
< Collection >
< PropertyPath >Currency</ PropertyPath >
</ Collection >
</ PropertyValue >
</ Record >
</ Annotation >
```
```
< Annotation Term="Aggregation.CustomAggregate" Qualifier="Forecast"
String="Edm.Decimal">
< Annotation Term="Aggregation.ContextDefiningProperties">
< Collection >
< PropertyPath >Currency/Code</ PropertyPath >
</ Collection >
</ Annotation >
</ Annotation >
</ EntitySet >
```
```
< EntitySet Name="Currencies" EntityType="SalesModel.Currency">
< Annotation Term="Aggregation.ApplySupported">
< Record >
< PropertyValue Property="GroupableProperties">
```

**5.5 Hierarchies**

A hierarchy is an arrangement of entities whose values are represented as being “above”, “below”, or “at the same level

as” one another.

🚧 Recursive hierarchies are defined in the following subsection. Any list of properties can be viewed as a leveled

hierarchy with a fixed number of levels, for example, year, quarter and month, but this is not made explicit in the OData
service.

**5.5.1 Recursive Hierarchy**

A recursive hierarchy is defined on a collection of entities by

```
determining which entities are part of the hierarchy and giving every such entity a single primitive non-null value
that uniquely identifies it within the hierarchy. These entities are called nodes , and the primitive value is called the
node identifier , and
associating with every node zero or more nodes from the same collection, called its parent nodes.
```
The recursive hierarchy is described in the model by an annotation of the entity type with the complex term
**RecursiveHierarchy** with these properties:

```
The NodeProperty MUST be a path with single-valued segments ending in a primitive property. This property
holds the node identifier of an entity that is a node in the hierarchy.
The ParentNavigationProperty MUST be a collection-valued or nullable single-valued navigation property
path that addresses the entity type annotated with this term. It navigates from an entity that is a node in the
hierarchy to its parent nodes.
```
The term **RecursiveHierarchy** can only be applied to entity types, and MUST be applied with a qualifier, which is

used to reference the hierarchy in transformations operating on recursive hierarchies and in hierarchy functions. The

same entity can serve as nodes in different recursive hierarchies, given different qualifiers.

A _root node_ is a node without parent nodes. A recursive hierarchy can have one or more root nodes. A node is a _child_

_node_ of its parent nodes, a node without child nodes is a _leaf node_. Two nodes with a common parent node are _sibling
nodes_ and so are two root nodes.

The _descendants with maximum distance_ of a node are its child nodes and, if , the descendants of these

child nodes with maximum distance. The _descendants_ are the descendants with maximum distance. A node

together with its descendants forms a _sub-hierarchy_ of the hierarchy.

The _ancestors with maximum distance_ of a node are its parent nodes and, if , the ancestors of these parent

nodes with maximum distance. The _ancestors_ are the ancestors with maximum distance. The
**ParentNavigationProperty** MUST be such that no node is an ancestor of itself, in other words: cycles are

forbidden.

**5.5.1.1 Hierarchy Functions**

For testing the position of a given entity in a recursive hierarchy, the **Aggregation** vocabulary **[OData-VocAggr]**

defines unbound functions. These have

```
< Collection >
< PropertyPath >Code</ PropertyPath >
</ Collection >
</ PropertyValue >
</ Record >
</ Annotation >
</ EntitySet >
</ EntityContainer >
```
```
d ≥ 1 d > 1
d − 1 d =∞
```
```
d ≥ 1 d > 1
d − 1 d =∞
```

```
a parameter pair HierarchyNodes , HierarchyQualifier where HierarchyNodes is a collection and
HierarchyQualifier is the qualifier of a RecursiveHierarchy annotation on its common entity type. The
node identifiers in this collection define the recursive hierarchy.
a parameter Node that contains the node identifier of the entity to be tested. Note that the test result depends only
on this node identifier, not on any other property of the given entity
additional parameters, depending on the type of test (see below)
a Boolean return value for the outcome of the test.
```
The following functions are defined:

```
isnode tests if the given entity is a node of the hierarchy.
isroot tests if the given entity is a root node of the hierarchy.
isdescendant tests if the given entity is a descendant with maximum distance MaxDistance of an ancestor
node (whose node identifier is given in a parameter Ancestor ), or equals the ancestor if IncludeSelf is true.
isancestor tests if the given entity is an ancestor with maximum distance MaxDistance of a descendant node
(whose node identifier is given in a parameter Descendant ), or equals the descendant if IncludeSelf is true.
issibling tests if the given entity and another entity (whose node identifier is given in a parameter Other ) are
sibling nodes.
isleaf tests if the given entity is a leaf node.
```
**5.5.2 Hierarchy Examples**

The hierarchy terms can be applied to the Example Data Model.

⚠ _Example 46: leveled hierarchies for products and time, and a recursive hierarchy for the sales organizations:_

The recursive hierarchy **SalesOrgHierarchy** can be used in functions with the **$filter** system query option.

_Example 47: requesting all organizations below EMEA_

```
GET /service/SalesOrganizations?$filter=Aggregation.isdescendant(
HierarchyNodes=$root/SalesOrganizations,
HierarchyQualifier='SalesOrgHierarchy',
```
```
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
< edmx:Edmx xmlns:edmx="http://docs.oasis-open.org/odata/ns/edmx"
Version="4.0">
< edmx:Reference Uri="https://docs.oasis-open.org/odata/odata-data-
aggregation-ext/v4.0/cs04/vocabularies/Org.OData.Aggregation.V1.xml">
< edmx:Include Alias="Aggregation"
Namespace="Org.OData.Aggregation.V1" />
</ edmx:Reference >
< edmx:DataServices >
< Schema xmlns="http://docs.oasis-open.org/odata/ns/edm"
Alias="SalesModel" Namespace="org.example.odata.salesservice">
< Annotations Target="SalesModel.SalesOrganization">
< Annotation Term="Aggregation.RecursiveHierarchy"
Qualifier="SalesOrgHierarchy">
< Record >
< PropertyValue Property="NodeProperty"
PropertyPath="ID" />
< PropertyValue Property="ParentNavigationProperty"
PropertyPath="Superordinate" />
</ Record >
</ Annotation >
</ Annotations >
</ Schema >
</ edmx:DataServices >
</ edmx:Edmx >
```

```
Node=ID,
Ancestor='EMEA')
```
_results in_

_Example 48: requesting just those organizations directly below EMEA_

```
GET /service/SalesOrganizations?$filter=Aggregation.isdescendant(
HierarchyNodes=$root/SalesOrganizations,
HierarchyQualifier='SalesOrgHierarchy',
Node=ID,
Ancestor='EMEA',
MaxDistance=1)
```
_results in_

_Example 49: just the lowest-level organizations_

```
GET /service/SalesOrganizations?$filter=Aggregation.isleaf(
HierarchyNodes=$root/SalesOrganizations,
HierarchyQualifier='SalesOrgHierarchy',
Node=ID)
```
_results in_

_Example 50: the lowest-level organizations including their superordinate’s_ **ID**

```
GET /service/SalesOrganizations?$filter=Aggregation.isleaf(
HierarchyNodes=$root/SalesOrganizations,
HierarchyQualifier='SalesOrgHierarchy',
Node=ID)
&$expand=Superordinate($select=ID)
```
### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "EMEA Central", "Name": "EMEA Central" },
{ "ID": "Sales Netherlands", "Name": "Sales Netherlands" },
{ "ID": "Sales Germany", "Name": "Sales Germany" },
{ "ID": "EMEA South", "Name": "EMEA South" },
...
{ "ID": "EMEA North", "Name": "EMEA North" },
...
]
}
```
### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "EMEA Central", "Name": "EMEA Central" },
{ "ID": "EMEA South", "Name": "EMEA South" },
{ "ID": "EMEA North", "Name": "EMEA North" },
...
]
}
```
### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "Sales Office London", "Name": "Sales Office London" },
{ "ID": "Sales Office New York", "Name": "Sales Office New York" },
...
]
}
```

_results in_

_Example 51: the sales_ **ID** _s involving sales organizations from EMEA_

```
GET /service/Sales?$select=ID&$filter=Aggregation.isdescendant(
HierarchyNodes=$root/SalesOrganizations,
HierarchyQualifier='SalesOrgHierarchy',
Node=SalesOrganization/ID,
Ancestor='EMEA')
```
_results in_

Further examples for recursive hierarchies using transformations operating on the hierarchy structure are provided in

section 7.9.

**5.6 Functions on Aggregated Entities**

Service-defined bound functions that serve as set transformations MAY be annotated with the term
**AvailableOnAggregates** to indicate that they are applicable to aggregated entities under specific conditions:

```
The RequiredProperties collection lists all properties that must be available in the aggregated entities;
otherwise, the annotated function will be inapplicable.
```
_Example 52: assume the product is an implicit input for a function bound to a collection of_ **Sales** _, then aggregating away the product makes this
function inapplicable._

### {

```
"@context": "$metadata#SalesOrganizations(*,Superordinate(ID))",
"value": [
{ "ID": "Sales Office London", "Name": "Sales Office London",
"Superordinate": { "ID": "EMEA United Kingdom" } },
{ "ID": "Sales Office New York", "Name": "Sales Office New York",
"Superordinate": { "ID": "US East" } },
...
]
}
```
### {

```
"@context": "$metadata#Sales(ID)",
"value": [
{ "ID": 6 },
{ "ID": 7 },
{ "ID": 8 }
]
}
```

# 6 Hierarchical Transformations

The transformations defined in this section are called hierarchical, because they make use of a recursive hierarchy and

are defined in terms of hierarchy functions introduced in the previous section.

The transformations **ancestors** and **descendants** do not define an order on the output set. An order can be imposed

by a subsequent **orderby** or **traverse** transformation or a **$orderby**. The output set of **traverse** is in preorder or

postorder.

The algorithmic descriptions of the transformations make use of a _union_ of collections, this is defined as an unordered
collection containing the items from all these collections and from which duplicates have been removed.

The notation is used to denote the value of a property , possibly preceded by a type-cast segment, in an instance.

It is also used to denote the value of a single-valued data aggregation path , evaluated relative to. The value of a
collection-valued data aggregation path is denoted in the notation by.

The notations introduced here are used throughout the following subsections.

## 6.1 Common Parameters for Hierarchical Transformations

The parameter lists defined in the following subsections have three mandatory parameters in common.

The recursive hierarchy is defined by a parameter pair , where and MUST be specified as the first and

second parameter. Here, MUST be an expression of type **Collection(Edm.EntityType)** starting with **$root** that

has no multiple occurrences of the same entity. identifies the collection of node entities forming a recursive hierarchy

based on an annotation of their common entity type with term **RecursiveHierarchy** with a **Qualifier** attribute

whose value MUST be provided in. The property paths referenced by **NodeProperty** and

**ParentNavigationProperty** in the **RecursiveHierarchy** annotation must be evaluable for the nodes in the

recursive hierarchy, otherwise the service MUST reject the request. The **NodeProperty** is denoted by in this section.

The third parameter MUST be a data aggregation path with single- or collection-valued segments whose last segment
MUST be a primitive property. The node identifier(s) of an instance in the input set are the primitive values in ,

they are reached via starting from. Let with be the concatenation where each sub-path

consists of a collection-valued segment that is preceded by zero or more single-valued segments, and either
consists of one or more single-valued segments or and is absent. Each segment can be prefixed with a type

cast.

## 6.2 Hierarchical Transformations Producing a Subset

These transformations produce an output set that consists of certain instances from their input set, possibly with
repetitions or in a different order.

## 6.2.1 Transformations ancestors and descendants

In the simple case, the **ancestors** transformation takes an input set consisting of instances that belong to a recursive

hierarchy. It determines a subset of the input set and then determines the set of ancestors of that were

already contained in the input set. Its output set is the ancestors set, optionally including.

In the more complex case, the instances in the input set are instead related to nodes in a recursive hierarchy. Then the

**ancestors** transformation determines a subset of the input set consisting of instances that are related to certain

nodes in the hierarchy, called start nodes. The ancestors of these start nodes are then determined, and the output set
consists of instances of the input set that are related to the ancestors, or optionally to the start nodes.

The **descendants** transformation works analogously, but with descendants.

```
, and are the first three parameters defined above.
```
```
u [ t ] t u
t u
Γ γ ( u , t )
```
```
( H , Q ) H Q
H
H
```
```
Q
```
```
q
```
```
p
u γ ( u , p )
p u p = p 1 /.../ pk / r k ≥ 0
```
_p_ 1 ,..., _pk r
k_ ≥ 1 / _r_

```
( H , Q ) A A
A
```
```
A
```
_H Q p_


The fourth parameter is a transformation sequence composed of transformations listed section 3.3 or section 6.2.1

and of service-defined bound functions whose output set is a subset of their input set. is the output set of this
sequence applied to the input set.

The fifth parameter is optional and takes an integer greater than or equal to 1 that specifies the maximum distance

between start nodes and ancestors or descendants to be considered. An optional final **keep start** parameter drives

the optional inclusion of the subset or start nodes.

The output set of the transformation or is

defined as the union of the output sets of transformations applied to the input set for all in. For a given instance

, the transformation determines all instances of the input set whose node identifier is an ancestor or descendant
of the node identifier of :

If contains only single-valued segments, then, for **ancestors** ,

or, for **descendants** ,

Otherwise with , in this case the output set of the transformation is defined as the union of

the output sets of transformations applied to the input set for all in. The output set of consists of the

instances of the input set whose node identifier is an ancestor or descendant of the node identifier :

For **ancestors** ,

```
T
A
```
```
d
```
```
ancestors( H , Q , p , T , d ,keep start) descendants( H , Q , p , T , d ,keep start)
F ( u ) u A
```
_u F_ ( _u_ )
_u_

```
p
```
```
F ( u )=filter(Aggregation.isancestor(
HierarchyNodes= H ,HierarchyQualifier=′ Q ′,
Node= p ,Descendant= u [ p ],MaxDistance= d ,IncludeSelf=true))
```
```
F ( u )=filter(Aggregation.isdescendant(
HierarchyNodes= H ,HierarchyQualifier=′ Q ′,
Node= p ,Ancestor= u [ p ],MaxDistance= d ,IncludeSelf=true)).
```
```
p = p 1 /.../ pk / r k ≥ 1 F ( u )
G ( n ) n γ ( u , p ) G ( n )
n
```
```
G ( n )=filter(
p 1 /any( y 1 :
y 1 / p 2 /any( y 2 :
```
```
⋱
yk − 1 / pk /any( yk :
Aggregation.isancestor(
HierarchyNodes= H ,HierarchyQualifier=′ Q ′,
Node= yk / r ,Descendant= n ,MaxDistance= d ,IncludeSelf=true
) ) ⋰ ) ) )
```

or, for **descendants** ,

where denote **lambdaVariableExpr** s as defined in **[OData-ABNF]** and may be absent.

If parameter is absent, the parameter is omitted. If **keep start** is absent, the parameter

```
is omitted.
```
Since the output set of **ancestors** is constructed as a union, no instance from the input set will occur more than once

in it, even if, for example, a sale is related to both a sales organization and one of its ancestor organizations. For
**descendants** , analogously.

_Example 53: Request based on the_ **SalesOrgHierarchy** _defined in Hierarchy Examples, with_ **Superordinate/$ref** _expanded to illustrate
the hierarchy relation_

```
GET /service/SalesOrganizations?$apply=
ancestors($root/SalesOrganizations,SalesOrgHierarchy,ID,
filter(contains(Name,'East') or contains(Name,'Central')))
&$expand=Superordinate/$ref
```
_results in_

_Example 54: Request based on the_ **SalesOrgHierarchy** _defined in Hierarchy Examples, with_ **Superordinate/$ref** _expanded to illustrate
the hierarchy relation_

```
GET /service/SalesOrganizations?$apply=
descendants($root/SalesOrganizations,SalesOrgHierarchy,ID,
filter(Name eq 'US'),keep start)
&$expand=Superordinate/$ref
```
_results in_

```
G ( n )=filter(
p 1 /any( y 1 :
y 1 / p 2 /any( y 2 :
```
```
⋱
yk − 1 / pk /any( yk :
Aggregation.isdescendant(
HierarchyNodes= H ,HierarchyQualifier=′ Q ′,
Node= yk / r ,Ancestor= n ,MaxDistance= d ,IncludeSelf=true
) ) ⋰ ) ) )
```
```
y 1 ,..., yk / r
```
```
d MaxDistance= d
```
IncludeSelf=true

### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "EMEA", "Name": "EMEA",
"Superordinate": { "@id": "SalesOrganizations('Sales')" } },
{ "ID": "US", "Name": "US",
"Superordinate": { "@id": "SalesOrganizations('Sales')" } },
{ "ID": "Sales", "Name": "Sales",
"Superordinate": null }
]
}
```

⚠ _Example 55: Input set and recursive hierarchy from two different entity sets_

```
GET /service/Sales?$apply=
ancestors($root/SalesOrganizations,
SalesOrgHierarchy,
SalesOrganization/ID,
filter(contains(SalesOrganization/Name,'East')
or contains(SalesOrganization/Name,'Central')),
keep start)
```
_results in_

**6.2.2 Transformation traverse**

The **traverse** transformation returns instances of the input set that are or are related to nodes of a given recursive

hierarchy in a specified tree order.

🚧 This version of the specification defines the behavior of the **traverse** transformation only in recursive hierarchies

where **RecursiveHierarchy/ParentNavigationProperty** is single-valued.

```
, and are the first three parameters defined above.
```
The fourth parameter of the **traverse** transformation is either **preorder** or **postorder**. Let be the collection of

root nodes in the recursive hierarchy. Nodes in are called start nodes in this subsection (see example 91).

Let be the list of all following parameters that are expressions which could also be passed as a **$orderby** system

query option, if there are any. If is present, the transformation stable-sorts by.

🚧 Future versions of this specification MAY allow an optional fifth parameter that comes before the parameter list and
could not be passed as a **$orderby** system query option.

The instances in the input set are related to one node (if is single-valued) or multiple nodes (if is collection-valued) in

the recursive hierarchy. Given a node , denote by the collection of all instances in the input set that are related to

### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "US West", "Name": "US West",
"Superordinate": { "@id": "SalesOrganizations('US')" } },
{ "ID": "US", "Name": "US",
"Superordinate": { "@id": "SalesOrganizations('Sales')" } },
{ "ID": "US East", "Name": "US East",
"Superordinate": { "@id": "SalesOrganizations('US')" } }
]
}
```
### {

```
"@context": "$metadata#Sales",
"value": [
{ "ID": "4", "Amount": 8 ,
"SalesOrganization": { "ID": "US East", "Name": "US East" } },
{ "ID": "5", "Amount": 4 ,
"SalesOrganization": { "ID": "US East", "Name": "US East" } },
{ "ID": "6", "Amount": 2 ,
"SalesOrganization": { "ID": "EMEA Central", "Name": "EMEA Central" } },
{ "ID": "7", "Amount": 1 ,
"SalesOrganization": { "ID": "EMEA Central", "Name": "EMEA Central" } },
{ "ID": "8", "Amount": 2 ,
"SalesOrganization": { "ID": "EMEA Central", "Name": "EMEA Central" } }
]
}
```
_H Q p_

```
h H ′
( H , Q ) H ′
```
```
o
o H ′ o
```
```
o
```
```
p p
x F ^( x )
```

```
; these collections can overlap. For each in , the output set contains one instance that comprises the properties
```
of and additional properties that identify the node. These additional properties are independent of and are bundled

into an instance called. For example, if a sale is related to two sales organizations and hence contained in both

```
and , the output set will contain two instances and and contributes a navigation
```
property **SalesOrganization**.

A transformation is defined below such that is the output set of applied to the input set of the **traverse**

transformation.

Given a node , the formulas below contain the transformation in order to inject the properties of into the

instances in ; this uses the function that is defined in the simple grouping section. Further, is a list of data

aggregation paths that shall be present in the output set, and is a function that maps each hierarchy node to an
instance of the input type containing the paths from. As a consequence of the following definitions, only single-valued

properties and “final segments from ” are nested into , therefore the behavior of is well-defined.

The definition of makes use of a function , which returns a sparsely populated instance in which only the

path has a value, namely.

Three cases are distinguished:

1. _Case where the recursive hierarchy is defined on the input set_
    This case applies if the paths and are equal. Let and let be a list containing all structural and
    navigation properties of the entity type of.
    In this case injects all properties of into the instances of the output set. (See example 57.)
2. _Case where the recursive hierarchy is defined on the related entity type addressed by a navigation property path_
    This case applies if is a non-empty navigation property path and an optional type-cast segment such that
    equals the concatenated path. Let and let.
    In this case injects the whole related entity into the instances of the output set. The navigation property
    path is expanded by default. (See example 58.)
3. _Case where the recursive hierarchy is related to the input set only through equality of node identifiers, not through_
    _navigation_
    If neither case 1 nor case 2 applies, let and let.
    In this case injects only the node identifier of into the instances of the output set.

Here paths are considered equal if their non-type-cast segments refer to the same model elements when evaluated
relative to the input set (see example 59).

The function takes an instance, a path and another instance as arguments and is defined recursively as

follows:

1. If equals the special symbol , set to a new instance of the input type without properties and without entity-id.
2. If contains only one segment other than a type cast, let , and let , then go to step 6.
3. Otherwise, let be the first property segment in , possibly together with a preceding type-cast segment, let be
    any type-cast segment that immediately follows, and let be the remainder such that equals the concatenated
    path where may be absent.
4. Let be an instance of the type of without properties and without entity-id.
5. Let.
6. If is single-valued, let.
7. If is collection-valued, let be a collection consisting of one item.
8. Return.

(See example 88.)

Since start nodes are root nodes, is computed exactly once for every node , as part of the recursive formula for

```
given below.
```
_x u F_ ^( _x_ )

```
u x u
σ ( x ) u
```
_F_ ^( _x_ 1 ) _F_ ^( _x_ 2 ) ( _u_ , _σ_ ( _x_ 1 )) ( _u_ , _σ_ ( _x_ 2 )) _σ_ ( _xi_ )

```
F ( x ) F ^( x ) F ( x )
```
```
x Π G ( σ ( x )) σ ( x )
F ^( x ) Π G G
σ x
G
G σ ( x ) Π G ( σ ( x ))
```
```
σ ( x ) a ( ε , t , x ) u
t u [ t ]= x
```
```
p q σ ( x )= x G
H
Π G ( σ ( x )) x
```
```
p ′ p ′′ p
p ′/ p ′′/ q σ ( x )= a ( ε , p ′/ p ′′, x ) G =( p ′)
Π G ( σ ( x )) x
p ′
```
```
σ ( x )= a ( ε , p , x [ q ]) G =( p )
Π G ( σ ( x )) x
```
```
a ( u , t , x )
```
```
u ε u
t t 1 = t x ′= x
t 1 t t 2
t 3 t
t 1 / t 2 / t 3 / t 2
u ′ t 1 / t 2
x ′= a ( u ′, t 3 , x )
t 1 u [ t 1 ]= x ′
t 1 u [ t 1 ] x ′
u
```
```
σ ( x ) x
```
_R_ ( _x_ )


Let be a sequence of the start nodes in preserving the order of stable-sorted by. Then the

transformation is defined as equivalent to

```
is a transformation producing the specified tree order for a sub-hierarchy of with root node. Let with
be an order-preserving sequence of the children of in. The recursive formula for is as follows:
```
If , then

If , then

The absence of cycles guarantees that the recursion terminates.

```
is a transformation that determines for the specified node the instances of the input set having the same node
```
identifier as.

If contains only single-valued segments, then

Otherwise with and

where denote **lambdaVariableExpr** s and may be absent.

_Example 56: Based on the_ **SalesOrgHierarchy** _defined in Hierarchy Examples_

```
GET /service/SalesOrganizations?$apply=
descendants($root/SalesOrganizations,SalesOrgHierarchy,ID,
Name eq 'US',keep start)
/ancestors($root/SalesOrganizations,SalesOrgHierarchy,ID,
contains(Name,'East'),keep start)
/traverse($root/SalesOrganizations,SalesOrgHierarchy,ID,preorder)
&$expand=Superordinate/$ref
```
_results in_

```
r 1 ,..., rn H ′ H ′ o
traverse( H , Q , p , h , o )
```
```
concat( R ( r 1 ),..., R ( rn )).
```
_R_ ( _x_ ) _H x c_ 1 ,..., _cm_

_m_ ≥ 0 _x_ ( _H_ , _Q_ ) _R_ ( _x_ )

```
h =preorder
```
```
R ( x )=concat( F ( x )/Π G ( σ ( x )), R ( c 1 ),..., R ( cm )).
```
```
h =postorder
```
```
R ( x )=concat( R ( c 1 ),..., R ( cm ), F ( x )/Π G ( σ ( x ))).
```
_F_ ( _x_ ) _x_

```
x
```
```
p
```
```
F ( x )=filter( p eq x [ q ]).
```
```
p = p 1 /.../ pk / r k ≥ 1
```
```
F ( x )=filter(
p 1 /any( y 1 :
y 1 / p 2 /any( y 2 :
```
```
⋱
yk − 1 / pk /any( yk :
yk / r eq x [ q ]
)
⋰
)
)
)
```
```
y 1 ,..., yk / r
```
### {

```
"@context": "$metadata#SalesOrganizations",
"value": [
{ "ID": "US", "Name": "US",
"Superordinate": { "@id": "SalesOrganizations('Sales')" } },
{ "ID": "US East", "Name": "US East",
"Superordinate": { "@id": "SalesOrganizations('US')" } }
```

_Example 57 : Postorder traversal of organizations in the hierarchy defined in Hierarchy Examples with (case 1 of the definition of
). In this case writes back the entire node into the output set of._

```
GET /service/SalesOrganizations?$apply=
traverse($root/SalesOrganizations,SalesOrgHierarchy,ID,postorder)
&$select=ID,Name
&$expand=Superordinate($select=ID)
```
_results in_

⚠ _Example 58 : Postorder traversal of sales per organization in the hierarchy defined in Hierarchy Examples with
and (case 2 of the definition of )._

```
GET /service/Sales?$apply=traverse(
$root/SalesOrganizations,
SalesOrgHierarchy,
SalesOrganization/ID,
postorder)
&$select=ID
&$expand=SalesOrganization($select=ID)
```
_The result contains each sale once for every organization to which it belongs, directly or indirectly._

⚠ _Example 59 : Although and , they are not equal in the sense of case 1, because they are evaluated relative to different entity
sets. Hence, this is an example of case 3 of the definition of , where no_ **Sales/ID** _matches a_ **SalesOrganizations/ID** _, that is, all
have empty output sets._

### ]

### }

```
p = q =ID σ ( x )
Π G ( σ ( x )) T
```
### {

```
"@context":
"$metadata#SalesOrganizations(ID,Name,Superordinate(ID))",
"value": [
{ "ID": "US West", "Name": "US West",
"Superordinate": { "ID": "US" } },
{ "ID": "US East", "Name": "US East",
"Superordinate": { "ID": "US" } },
{ "ID": "US", "Name": "US",
"Superordinate": { "ID": "Sales" } },
{ "ID": "EMEA Central", "Name": "EMEA Central",
"Superordinate": { "ID": "EMEA" } },
{ "ID": "EMEA", "Name": "EMEA",
"Superordinate": { "ID": "Sales" } },
{ "ID": "Sales", "Name": "Sales",
"Superordinate": null }
]
}
```
_p_ = _p_ ′/ _q_ =SalesOrganization/ID _p_ ′=SalesOrganization _σ_ ( _x_ )

### {

```
"@context": "$metadata#Sales(ID,SalesOrganization(ID))",
"value": [
{ "ID": 1 , "SalesOrganization": { "ID": "US West" } },
{ "ID": 2 , "SalesOrganization": { "ID": "US West" } },
{ "ID": 3 , "SalesOrganization": { "ID": "US West" } },
{ "ID": 4 , "SalesOrganization": { "ID": "US East" } },
{ "ID": 5 , "SalesOrganization": { "ID": "US East" } },
{ "ID": 1 , "SalesOrganization": { "ID": "US" } },
{ "ID": 2 , "SalesOrganization": { "ID": "US" } },
{ "ID": 3 , "SalesOrganization": { "ID": "US" } },
{ "ID": 4 , "SalesOrganization": { "ID": "US" } },
{ "ID": 5 , "SalesOrganization": { "ID": "US" } },
...
]
}
```
```
p =ID q =ID
σ ( x ) F ( x )
```

```
GET /service/Sales?$apply=traverse(
$root/SalesOrganizations,
SalesOrgHierarchy,
ID,
postorder)
```
_results in_

### {

```
"@context": "$metadata#Sales(ID,SalesOrganization(ID))",
"value": []
}
```

# 7 Examples

The following examples show some common aggregation-related questions that can be answered by combining the

transformations defined in sections 3 and 6.

## 7.1 Requesting Distinct Values

Grouping without specifying a set transformation returns the distinct combination of the grouping properties.

_Example 60:_

```
GET /service/Customers?$apply=groupby((Name))
```
_results in_

_Note that “Sue” appears only once although the customer base contains two different Sues._

Aggregation is also possible across related entities.

_Example 61: customers that bought something_

```
GET /service/Sales?$apply=groupby((Customer/Name))
```
_results in_

_Since_ **groupby** _expands navigation properties in grouping properties by default, this is the same result as if the request would include a_
**$expand=Customer($select=Name)**_. The_ **groupby** _removes all other properties._

_Note that “Luc” does not appear in the aggregated result as he hasn’t bought anything and therefore there are no sales entities that
refer/navigate to Luc._

_However, even though both Sues bought products, only one “Sue” appears in the aggregate result. Including properties that guarantee the right
level of uniqueness in the grouping can repair that._

_Example 62:_

```
GET /service/Sales?$apply=groupby((Customer/Name,Customer/ID))
```
_results in_

### {

```
"@context": "$metadata#Customers(Name)",
"value": [
{ "Name": "Luc" },
{ "Name": "Joe" },
{ "Name": "Sue" }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Name))",
"value": [
{ "Customer": { "Name": "Joe" } },
{ "Customer": { "Name": "Sue" } }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Name,ID))",
"value": [
{ "Customer": { "Name": "Joe", "ID": "C1" } },
{ "Customer": { "Name": "Sue", "ID": "C2" } },
{ "Customer": { "Name": "Sue", "ID": "C3" } }
```

_This could also have been formulated as_

```
GET /service/Sales?$apply=groupby((Customer))
&$expand=Customer($select=Name,ID)
```
_Example 63 : Grouping by navigation property_ **Customer**

```
GET /service/Sales?$apply=groupby((Customer))
```
_results in_

_Example 64: the first question in the motivating example in section 2.3, which customers bought which products, can now be expressed as_

```
GET /service/Sales?$apply=groupby((Customer/Name,Customer/ID,Product/Name))
```
_and results in_

⚠ _Example 65 : grouping by properties of subtypes_

```
GET /service/Products?$apply=groupby((SalesModel.FoodProduct/Rating,
SalesModel.NonFoodProduct/RatingClass))
```
_results in_

### ]

### }

### {

```
"@context": "$metadata#Sales(Customer())",
"value": [
{ "Customer": { "ID": "C1", "Name": "Joe", "Country": "USA" } },
{ "Customer": { "ID": "C2", "Name": "Sue", "Country": "USA" } },
{ "Customer": { "ID": "C3", "Name": "Sue", "Country": "Netherlands" } }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Name,ID),Product(Name))",
"value": [
{ "Customer": { "Name": "Joe", "ID": "C1" },
"Product": { "Name": "Coffee"} },
{ "Customer": { "Name": "Joe", "ID": "C1" },
"Product": { "Name": "Paper" } },
{ "Customer": { "Name": "Joe", "ID": "C1" },
"Product": { "Name": "Sugar" } },
{ "Customer": { "Name": "Sue", "ID": "C2" },
"Product": { "Name": "Coffee"} },
{ "Customer": { "Name": "Sue", "ID": "C2" },
"Product": { "Name": "Paper" } },
{ "Customer": { "Name": "Sue", "ID": "C3" },
"Product": { "Name": "Paper" } },
{ "Customer": { "Name": "Sue", "ID": "C3" },
"Product": { "Name": "Sugar" } }
]
}
```
### {

```
"@context": "$metadata#Products(SalesModel.FoodProduct/Rating,
SalesModel.NonFoodProduct/RatingClass)",
"value": [
{ "@type": "#SalesModel.FoodProduct", "Rating": 5 },
{ "@type": "#SalesModel.FoodProduct", "Rating": null },
{ "@type": "#SalesModel.NonFoodProduct", "RatingClass": "average" },
```

⚠ _Example 66 : grouping by a property of a subtype_

```
GET /service/Products?$apply=groupby((SalesModel.FoodProduct/Rating))
```
_results in a third group representing entities with no_ **SalesModel.FoodProduct/Rating** _, including the_ **SalesModel.NonFoodProduct** _s:_

**7.2 Standard Aggregation Methods**

The client may specify one of the predefined aggregation methods **min** , **max** , **sum** , **average** , and **countdistinct** , or

a custom aggregation method, to aggregate an aggregatable expression. Expressions defining an aggregate method
specify an alias. The aggregated values are returned in a dynamic property whose name is determined by the alias.

_Example 67 :_

```
GET /service/Products?$apply=groupby((Name),
aggregate(Sales/Amount with sum as Total))
```
_results in_

_Note that the base set of the request is_ **Products** _, so there is a result item for product_ **Pencil** _even though there are no sales items. The input
set for the aggregation in the third row is consisting of the pencil, , is empty and is also empty.
The sum over the empty collection is null._

_Example 68: Compute the aggregate as a property using the_ **aggregate** _function in_ **$compute** _:_

```
GET /service/Products?$compute=Sales/aggregate(Amount with sum) as Total
```
_results in_

```
{ "@type": "#SalesModel.NonFoodProduct", "RatingClass": null }
]
}
```
### {

```
"@context": "$metadata#Products(@Core.AnyStructure)",
"value": [
{ "@type": "#SalesModel.FoodProduct", "Rating": 5 },
{ "@type": "#SalesModel.FoodProduct", "Rating": null },
{ }
]
}
```
### {

```
"@context": "$metadata#Products(Name,Total)",
"value": [
{ "Name": "Coffee", "Total@type": "Decimal", "Total": 12 },
{ "Name": "Paper", "Total@type": "Decimal", "Total": 8 },
{ "Name": "Pencil", "Total": null },
{ "Name": "Sugar", "Total@type": "Decimal", "Total": 4 }
]
}
```
```
I p = q / r =Sales/Amount E =Γ( I , q ) A =Γ( E , r )
```
### {

```
"@context": "$metadata#Products(*,Total)",
"value": [
{ "ID": "P2", "Name": "Coffee", "Color": "Brown", "TaxRate": 0 .06,
"Total@type": "Decimal", "Total": 12 },
{ "ID": "P3", "Name": "Paper", "Color": "White", "TaxRate": 0. 14 ,
"Total@type": "Decimal", "Total": 8 },
{ "ID": "P4", "Name": "Pencil", "Color": "Black", "TaxRate": 0. 14 ,
"Total": null },
{ "ID": "P1", "Name": "Sugar", "Color": "White", "TaxRate": 0 .06,
"Total@type": "Decimal", "Total": 4 }
```

_Example 69: Alternatively,_ **join** _could be applied to yield a flat structure:_

```
GET /service/Products?$apply=
join(Sales as TotalSales,aggregate(Amount with sum as Total))
/groupby((Name,TotalSales/Total))
```
_results in_

_Applying_ **outerjoin** _instead would return an additional entity for product with_ **ID** _“Pencil” and_ **TotalSales** _having a null value._

_Example 70:_

```
GET /service/Sales?$apply=groupby((Customer/Country),
aggregate(Amount with average as AverageAmount))
```
_results in_

_Here the_ **AverageAmount** _is of type_ **Edm.Double**_._

_Example 71:_ **$count** _after navigation property_

```
GET /service/Products?$apply=groupby((Name),
aggregate(Sales/$count as SalesCount))
```
_results in_

### ]

### }

### {

```
"@context": "$metadata#Products(Name,TotalSales())",
"value": [
{ "Name": "Coffee",
"TotalSales@context": "#Sales(Total)/$entity",
"TotalSales": { "Total@type": "Decimal", "Total": 12 } },
{ "Name": "Paper",
"TotalSales@context": "#Sales(Total)/$entity",
"TotalSales": { "Total@type": "Decimal", "Total": 8 } },
{ "Name": "Sugar",
"TotalSales@context": "#Sales(Total)/$entity",
"TotalSales": { "Total@type": "Decimal", "Total": 4 } }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Country),AverageAmount)",
"value": [
{ "Customer": { "Country": "Netherlands" },
"AverageAmount": 1.6666666666666667 },
{ "Customer": { "Country": "USA" },
"AverageAmount": 3.8 }
]
}
```
### {

```
"@context": "$metadata#Products(Name,SalesCount)",
"value": [
{ "Name": "Coffee", "SalesCount@type": "Decimal", "SalesCount": 2 },
{ "Name": "Paper", "SalesCount@type": "Decimal", "SalesCount": 4 },
{ "Name": "Pencil", "SalesCount@type": "Decimal", "SalesCount": 0 },
{ "Name": "Sugar", "SalesCount@type": "Decimal", "SalesCount": 2 }
]
}
```

The **aggregate** function can not only be used in **$compute** but also in **$filter** and **$orderby** :

_Example 72: Products with an aggregated sales volume of ten or more_

```
GET /service/Products?$filter=Sales/aggregate(Amount with sum) ge 10
```
_results in_

_Example 73: Customers in descending order of their aggregated sales volume_

```
GET /service/Customers?$orderby=Sales/aggregate(Amount with sum) desc
```
_results in_

_Example 74: Contribution of each sales to grand total sales amount_

```
GET /service/Sales?$compute=Amount divby $these/aggregate(Amount with sum)
as Contribution
```
_results in_

_Example 75: Product categories with at least one product having an aggregated sales amount greater than 10_

### {

```
"@context": "$metadata#Products",
"value": [
{ "ID": "P2", "Name": "Coffee", "Color": "Brown", "TaxRate": 0 .06 },
{ "ID": "P3", "Name": "Paper", "Color": "White", "TaxRate": 0. 14 }
]
}
```
### {

```
"@context": "$metadata#Customers",
"value": [
{ "ID": "C2", "Name": "Sue", "Country": "USA" },
{ "ID": "C1", "Name": "Joe", "Country": "USA" },
{ "ID": "C3", "Name": "Sue", "Country": "Netherlands" },
{ "ID": "C4", "Name": "Luc", "Country": "France" }
]
}
```
### {

```
"@context": "$metadata#Sales(*,Contribution)",
"value": [
{ "ID": 1 , "Amount": 1 , "Contribution@type": "Decimal",
"Contribution": 0 .0416666666666667 },
{ "ID": 2 , "Amount": 2 , "Contribution@type": "Decimal",
"Contribution": 0 .0833333333333333 },
{ "ID": 3 , "Amount": 4 , "Contribution@type": "Decimal",
"Contribution": 0. 1666666666666667 },
{ "ID": 4 , "Amount": 8 , "Contribution@type": "Decimal",
"Contribution": 0. 3333333333333333 },
{ "ID": 5 , "Amount": 4 , "Contribution@type": "Decimal",
"Contribution": 0. 1666666666666667 },
{ "ID": 6 , "Amount": 2 , "Contribution@type": "Decimal",
"Contribution": 0 .0833333333333333 },
{ "ID": 7 , "Amount": 1 , "Contribution@type": "Decimal",
"Contribution": 0 .0416666666666667 },
{ "ID": 8 , "Amount": 2 , "Contribution@type": "Decimal",
"Contribution": 0 .0833333333333333 }
]
}
```

```
GET /service/Categories?$filter=Products/any(
p:p/Sales/aggregate(Amount with sum) gt 10)
```
_results in_

The **aggregate** function can also be applied inside **$apply** :

_Example 76: Sales volume per customer in relation to total volume_

```
GET /service/Sales?$apply=
groupby((Customer),aggregate(Amount with sum as CustomerAmount))
/compute(CustomerAmount divby $these/aggregate(CustomerAmount with sum)
as Contribution)
&$expand=Customer/$ref
```
_results in_

**7.3 Requesting Expanded Results**

_Example 77: use_ **outerjoin** _to split up collection-valued navigation properties for grouping_

```
GET /service/Customers?$apply=outerjoin(Sales as ProductSales)
/groupby((Country,ProductSales/Product/Name))
```
_returns the different combinations of products sold per country:_

### {

```
"@context": "$metadata#Categories",
"value": [
{ "ID": "PG1", "Name": "Food" }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(),CustomerAmount,Contribution)",
"value": [
{ "Customer": { "@id": "Customers('C1')" },
"Contribution@type": "Decimal", "Contribution": 0. 2916667 },
{ "Customer": { "@id": "Customers('C2')" },
"Contribution@type": "Decimal", "Contribution": 0. 5 },
{ "Customer": { "@id": "Customers('C3')" },
"Contribution@type": "Decimal", "Contribution": 0. 2083333 }
]
}
```
### {

```
"@context": "$metadata#Customers(Country,ProductSales())",
"value": [
{ "Country": "Netherlands",
"ProductSales@context": "#Sales(Product(Name))/$entity",
"ProductSales": { "Product": { "Name": "Paper" } } },
{ "Country": "Netherlands",
"ProductSales@context": "#Sales(Product(Name))/$entity",
"ProductSales": { "Product": { "Name": "Sugar" } } },
{ "Country": "USA",
"ProductSales@context": "#Sales(Product(Name))/$entity",
"ProductSales": { "Product": { "Name": "Coffee" } } },
{ "Country": "USA",
"ProductSales@context": "#Sales(Product(Name))/$entity",
"ProductSales": { "Product": { "Name": "Paper" } } },
{ "Country": "USA",
"ProductSales@context": "#Sales(Product(Name))/$entity",
"ProductSales": { "Product": { "Name": "Sugar" } } },
{ "Country": "France", "ProductSales": null }
```

**7.4 Requesting Custom Aggregates**

Custom aggregates are defined through the **CustomAggregate** annotation. They can be associated with an entity set,

a collection or an entity container.

A custom aggregate can be used by specifying the name of the custom aggregate in the **aggregate** clause.

_Example 78:_

```
GET /service/Sales?$apply=groupby((Customer/Country),
aggregate(Amount with sum as Actual,Forecast))
```
_results in_

When associated with an entity set a custom aggregate MAY have the same name as a property of the underlying entity

type with the same type as the type returned by the custom aggregate. This is typically done when the aggregate is
used as a default aggregate for that property.

_Example 79: A custom aggregate can be defined with the same name as a property of the same type in order to define a default aggregate for
that property._

```
GET /service/Sales?$apply=groupby((Customer/Country),aggregate(Amount))
```
_results in_

**7.5 Aliasing**

A property can be aggregated in multiple ways, each with a different alias.

_Example 80:_

```
GET /service/Sales?$apply=groupby((Customer/Country),
aggregate(Amount with sum as Total,
Amount with average as AvgAmt))
```
_results in_

### ]

### }

### {

```
"@context": "$metadata#Sales(Customer(Country),Actual,Forecast)",
"value": [
{ "Customer": { "Country": "Netherlands" },
"Actual@type": "Decimal", "Actual": 5 ,
"Forecast@type": "Decimal", "Forecast": 4 },
{ "Customer": { "Country": "USA" },
"Actual@type": "Decimal", "Actual": 19 ,
"Forecast@type": "Decimal", "Forecast": 21 }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Amount)",
"value": [
{ "Customer": { "Country": "Netherlands" }, "Amount": 5 },
{ "Customer": { "Country": "USA" }, "Amount": 19 }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Total,AvgAmt)",
```

There is no hard distinction between groupable and aggregatable properties: the same property can be aggregated and
used to group the aggregated results.

_Example 81:_

```
GET /service/Sales?$apply=groupby((Amount),aggregate(Amount with sum as Total))
```
_will return all distinct amounts appearing in sales orders and how much money was made with deals of this amount_

**7.6 Combining Transformations per Group**

Dynamic property names may be reused in different transformation sequences passed to **concat**.

_Example 82 : to get the best-selling product per country with sub-totals for every country, the partial results of a transformation sequence and a_
**groupby** _transformation are concatenated:_

```
GET /service/Sales?$apply=concat(
groupby((Customer/Country,Product/Name),
aggregate(Amount with sum as Total))
/groupby((Customer/Country),topcount(1,Total)),
groupby((Customer/Country),
aggregate(Amount with sum as Total)))
```
_results in_

```
"value": [
{ "Customer": { "Country": "Netherlands" },
"Total@type": "Decimal", "Total": 5 ,
"AvgAmt@type": "Decimal", "AvgAmt": 1.6666667 },
{ "Customer": { "Country": "USA" },
"Total@type": "Decimal", "Total": 19 ,
"AvgAmt@type": "Decimal", "AvgAmt": 3.8 }
]
}
```
### {

```
"@context": "$metadata#Sales(Amount,Total)",
"value": [
{ "Amount": 1 , "Total@type": "Decimal", "Total": 2 },
{ "Amount": 2 , "Total@type": "Decimal", "Total": 6 },
{ "Amount": 4 , "Total@type": "Decimal", "Total": 8 },
{ "Amount": 8 , "Total@type": "Decimal", "Total": 8 }
]
}
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Total)",
"value": [
{ "Customer": { "Country": "USA" }, "Product": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12
},
{ "Customer": { "Country": "Netherlands" }, "Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 3
},
{ "Customer": { "Country": "USA" },
"Total@type": "Decimal", "Total": 19
},
{ "Customer": { "Country": "Netherlands" },
"Total@type": "Decimal", "Total": 5
}
]
}
```

_Example 83: transformation sequences are also useful inside_ **groupby** _: Aggregate the amount by only considering the top two sales amounts
per product and country:_

```
GET /service/Sales?$apply=groupby((Customer/Country,Product/Name),
topcount(2,Amount)/aggregate(Amount with sum as Total))
```
_results in_

_Example 84 : concatenation of two different groupings “biggest sale per customer” and “biggest sale per product”, made distinguishable by a
dynamic property:_

```
GET /service/Sales?$apply=concat(
groupby((Customer),topcount(1,Amount))/compute('Customer' as per),
groupby((Product),topcount(1,Amount))/compute('Product' as per))
&$expand=Customer($select=ID),Product($select=ID)
```
_In the result,_ **Sales** _entities 4 and 6 occur twice each with contradictory values of the dynamic property_ **per**_. If a UI consuming the response
presents the two groupings in separate columns based on the_ **per** _property, no contradiction effectively arises._

**7.7 Model Functions as Set Transformations**

_Example 85: As a variation of example 82, a query for returning the best-selling product per country and the total amount of the remaining
products can be formulated with the help of a model function._

_For this purpose, the model includes a definition of a_ **TopCountAndRemainder** _function that accepts a count and a numeric property for the top
entities:_

### {

```
"@context": "$metadata#Sales(Customer(Country),Product(Name),Total)",
"value": [
{ "Customer": { "Country": "Netherlands" }, "Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 3
},
{ "Customer": { "Country": "Netherlands" }, "Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 2
},
{ "Customer": { "Country": "USA" }, "Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 2
},
{ "Customer": { "Country": "USA" }, "Product": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12
},
{ "Customer": { "Country": "USA" }, "Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 5
}
]
}
```
### {

```
"@context": "$metadata#Sales(*,per,Customer(ID),Product(ID))",
"value": [
{ "Customer": { "ID": "C1" }, "Product": { "ID": "P2" },
"ID": "3", "Amount": 4 , "per": "Customer" },
{ "Customer": { "ID": "C2" }, "Product": { "ID": "P2" },
"ID": "4", "Amount": 8 , "per": "Customer" },
{ "Customer": { "ID": "C3" }, "Product": { "ID": "P1" },
"ID": "6", "Amount": 2 , "per": "Customer" },
{ "Customer": { "ID": "C3" }, "Product": { "ID": "P1" },
"ID": "6", "Amount": 2 , "per": "Product" },
{ "Customer": { "ID": "C2" }, "Product": { "ID": "P2" },
"ID": "4", "Amount": 8 , "per": "Product" },
{ "Customer": { "ID": "C2" }, "Product": { "ID": "P3" },
"ID": "5", "Amount": 4 , "per": "Product" }
]
}
```

_The function retains those entities that_ **topcount** _also would retain, and replaces the remaining entities by a single aggregated entity, where
only the numeric property has a value, which is the sum over those remaining entities:_

```
GET /service/Sales?$apply=
groupby((Customer/Country,Product/Name),
aggregate(Amount with sum as Total))
/groupby((Customer/Country),
Self.TopCountAndRemainder(Count=1,Property='Total'))
```
_results in_

_Note that these two entities get their values for the Country property from the groupby transformation, which ensures that they contain all
grouping properties with the correct values._

**7.8 Controlling Aggregation per Rollup Level**

For a leveled hierarchy, consumers may specify a different aggregation method per level as a hierarchy level below the
root level.

_Example 86: get the average of the overall amount by month per product._

_Using a transformation sequence:_

```
GET /service/Sales?$apply=groupby((Product/ID,Product/Name,Time/Month),
aggregate(Amount with sum) as Total))
/groupby((Product/ID,Product/Name),
aggregate(Total with average as MonthlyAverage))
```
**7.9 Aggregation in Recursive Hierarchies**

⚠ _Example 87: The input set_ **Sales** _is filtered along a hierarchy on a related entity (navigation property_ **SalesOrganization** _) before an
aggregation_

```
GET /service/Sales?$apply=
descendants($root/SalesOrganizations,
SalesOrgHierarchy,
SalesOrganization/ID,
filter(SalesOrganization/Name eq 'US'),
```
```
< edm:Function Name="TopCountAndRemainder"
IsBound="true">
< edm:Parameter Name="EntityCollection"
Type="Collection(Edm.EntityType)" />
< edm:Parameter Name="Count" Type="Edm.Int16" />
< edm:Parameter Name="Property" Type="Edm.String" />
< edm:ReturnType Type="Collection(Edm.EntityType)" />
</ edm:Function >
```
### {

```
"@context": "$metadata#Sales(Customer(Country),Total)",
"value": [
{ "Customer": { "Country": "Netherlands" },
"Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 3 },
{ "Customer": { "Country": "Netherlands" },
"Total@type": "Decimal", "Total": 2 },
{ "Customer": { "Country": "USA" },
"Product": { "Name": "Coffee" },
"Total@type": "Decimal", "Total": 12 },
{ "Customer": { "Country": "USA" },
"Total@type": "Decimal", "Total": 7 }
]
}
```

```
keep start)
/aggregate(Amount with sum as TotalAmount)
```
_The same aggregate value is computed if the input set is the hierarchical entity_ **SalesOrganizations** _and an assumed partner navigation
property_ **Sales** _of_ **SalesOrganization** _appears in the_ **aggregate** _transformation_

```
GET /service/SalesOrganizations?$apply=
descendants($root/SalesOrganizations,
SalesOrgHierarchy,
ID,
filter(Name eq 'US'),
keep start)
/aggregate(Sales/Amount with sum as TotalAmount)
```
_Example 88 : Preorder traversal of a hierarchy with 1:N relationship with collection-valued segment and._

```
GET /service/Products?$apply=traverse(
$root/SalesOrganizations,
SalesOrgHierarchy,
Sales/SalesOrganization/ID,
preorder,
Name asc)
&$select=ID
```
_The result contains multiple instances of the same_ **Product** _that differ in their_ **Sales** _navigation property even though they agree in their_ **ID** _key
property. The node with_ **"US"** _has_ **{"Sales": [{"SalesOrganization": {"ID": "US"}}]}**_._

**7.10 Maintaining Recursive Hierarchies**

Besides changes to the structural properties of the entities in a hierarchical collection, hierarchy maintenance involves
changes to the parent-child relationships.

_Example 89: Move a sales organization Switzerland under the parent EMEA Central by binding the parent navigation property to EMEA Central_
**_[OData-JSON, section 8.5]_** _:_

```
p 1 =Sales r =SalesOrganization/ID
```
```
x x /ID= σ ( x )=
```
```
{
"@context":
"$metadata#Products(ID,Sales(SalesOrganization(ID)))",
"value": [
{ "ID": "P1", "Sales": [ { "SalesOrganization": { "ID": "Sales" } } ] },
{ "ID": "P2", "Sales": [ { "SalesOrganization": { "ID": "Sales" } } ] },
{ "ID": "P3", "Sales": [ { "SalesOrganization": { "ID": "Sales" } } ] },
{ "ID": "P1", "Sales": [ { "SalesOrganization": { "ID": "EMEA" } } ] },
{ "ID": "P3", "Sales": [ { "SalesOrganization": { "ID": "EMEA" } } ] },
{ "ID": "P1",
"Sales": [ { "SalesOrganization": { "ID": "EMEA Central" } } ] },
{ "ID": "P3",
"Sales": [ { "SalesOrganization": { "ID": "EMEA Central" } } ] },
{ "ID": "P1", "Sales": [ { "SalesOrganization": { "ID": "US" } } ] },
{ "ID": "P2", "Sales": [ { "SalesOrganization": { "ID": "US" } } ] },
{ "ID": "P3", "Sales": [ { "SalesOrganization": { "ID": "US" } } ] },
{ "ID": "P2", "Sales": [ { "SalesOrganization": { "ID": "US East" } } ] },
{ "ID": "P3", "Sales": [ { "SalesOrganization": { "ID": "US East" } } ] },
{ "ID": "P1", "Sales": [ { "SalesOrganization": { "ID": "US West" } } ] },
{ "ID": "P2", "Sales": [ { "SalesOrganization": { "ID": "US West" } } ] },
{ "ID": "P3", "Sales": [ { "SalesOrganization": { "ID": "US West" } } ] }
]
}
```
```
PATCH /service/SalesOrganizations('Switzerland')
Content-Type: application/json
```
```
{ "Superordinate": { "@id": "SalesOrganizations('EMEA Central')" } }
```

_results in_ **204 No Content**_._

_Deleting the parent from the sales organization Switzerland (making it a root) can be achieved either with:_

_or with:_

```
DELETE /service/SalesOrganizations('Switzerland')/Superordinate/$ref
```
_Example 90 : If the parent navigation property contained a referential constraint for the key of the target_ **_[OData-CSDL, section 8.5]_** _,_

_then alternatively the property taking part in the referential constraint_ **_[OData-Protocol, section 11.4.8.1]_** _could be changed to EMEA Central:_

If the parent-child relationship between sales organizations is maintained in a separate entity set, a node can have

multiple parents, with additional information on each parent-child relationship.

⚠ _Example 91 : Assume the relation from a node to its parent nodes contains a weight:_

```
PATCH /service/SalesOrganizations('Switzerland')
Content-Type: application/json
```
```
{ "Superordinate": { "@id": null } }
```
```
< EntityType Name="SalesOrganization">
< Key >
< PropertyRef Name="ID" />
</ Key >
< Property Name="ID" Type="Edm.String" Nullable="false" />
< Property Name="Name" Type="Edm.String" />
< Property Name="SuperordinateID" Type="Edm.String" />
< NavigationProperty Name="Superordinate"
Type="SalesModel.SalesOrganization">
< ReferentialConstraint Property="SuperordinateID"
ReferencedProperty="ID" />
</ NavigationProperty >
</ EntityType >
```
```
PATCH /service/SalesOrganizations('Switzerland')
Content-Type: application/json
```
```
{ "SuperordinateID": "EMEA Central" }
```
```
< EntityType Name="SalesOrganizationRelation">
< Key >
< PropertyRef Name="Superordinate/ID" Alias="SuperordinateID" />
</ Key >
< Property Name="Weight" Type="Edm.Decimal"
Nullable="false" DefaultValue="1" />
< NavigationProperty Name="Superordinate"
Type="SalesModel.SalesOrganization" Nullable="false" />
</ EntityType >
< EntityType Name="SalesOrganization">
< Key >
< PropertyRef Name="ID" />
</ Key >
< Property Name="ID" Type="Edm.String" Nullable="false" />
< Property Name="Name" Type="Edm.String" />
< NavigationProperty Name="Relations"
Type="Collection(SalesModel.SalesOrganizationRelation)"
Nullable="false" ContainsTarget="true" />
< Annotation Term="Aggregation.RecursiveHierarchy"
Qualifier="MultiParentHierarchy">
< Record >
< PropertyValue Property="NodeProperty"
PropertyPath="ID" />
```

_Further assume the following relationships between sales organizations:_

```
ID Relations/SuperordinateID Relations/Weight
US Sales 1
EMEA Sales 1
EMEA Central EMEA 1
Atlantis US 0.6
Atlantis EMEA 0.4
Phobos Mars 1
```
_Then Atlantis is a node with two parents. The standard hierarchical transformations_ **ancestors** _and_ **descendants** _disregard the weight
property and consider both parents equally valid. Transformation_ **traverse** _has no defined behavior._

_Since this example contains no referential constraint, there is no analogy to example 90. The alias_ **SuperordinateID** _cannot be used in the
payload, the following request is invalid:_

_The alias_ **SuperordinateID** _is used in the request to delete the added relationship again:_

```
DELETE /service/SalesOrganizations('Mars')/Relations('Sales')
```
**7.11 Transformation Sequences**

Applying aggregation first covers the most prominent use cases. The slightly more sophisticated question “how much
money is earned with small sales” requires filtering the base set before applying the aggregation. To enable this type of
question several transformations can be specified in **$apply** in the order they are to be applied, separated by a forward

slash.

_Example 92:_

```
GET /service/Sales?$apply=filter(Amount le 1)
/aggregate(Amount with sum as Total)
```
_means “filter first, then aggregate”, and results in_

Using **filter** within **$apply** does not preclude using it as a normal system query option.

_Example 93:_

```
GET /service/Sales?$apply=filter(Amount le 2)/groupby((Product/Name),
aggregate(Amount with sum as Total))
&$filter=Total ge 4
```
_results in_

```
< PropertyValue Property="ParentNavigationProperty"
NavigationPropertyPath="Relations/Superordinate" />
</ Record >
</ Annotation >
</ EntityType >
```
```
POST /service/SalesOrganizations('Mars')/Relations
Content-Type: application/json
```
```
{ "SuperordinateID": "Sales" }
```
### {

```
"@context": "$metadata#Sales(Total)",
"value": [
{ "Total@type": "Decimal", "Total": 2 }
]
}
```

For further examples, consider another data model containing entity sets for cities, countries and continents and the

obvious associations between them.

_Example 94: getting the population per country with_

```
GET /service/Cities?$apply=groupby((Continent/Name,Country/Name),
aggregate(Population with sum as TotalPopulation))
```
_results in_

_Example 95: all countries with megacities and their continents_

```
GET /service/Cities?$apply=filter(Population ge 10000000)
/groupby((Continent/Name,Country/Name),
aggregate(Population with sum as TotalPopulation))
```
_Example 96: all countries with tens of millions of city dwellers and the continents only for these countries_

```
GET /service/Cities?$apply=groupby((Continent/Name,Country/Name),
aggregate(Population with sum as CountryPopulation))
/filter(CountryPopulation ge 10000000)
/concat(identity,
groupby((Continent/Name),
aggregate(CountryPopulation with sum
as TotalPopulation)))
```
_or_

```
GET /service/Cities?$apply=groupby((Continent/Name,Country/Name),
aggregate(Population with sum as CountryPopulation))
/filter(CountryPopulation ge 10000000)
/concat(groupby((Continent/Name,Country/Name),
aggregate(CountryPopulation with sum
as TotalPopulation)),
groupby((Continent/Name),
aggregate(CountryPopulation with sum
as TotalPopulation)))
```
_Example 97: all countries with tens of millions of city dwellers and all continents with cities independent of their size_

```
GET /service/Cities?$apply=groupby((Continent/Name,Country/Name),
aggregate(Population with sum as CountryPopulation))
```
### {

```
"@context": "$metadata#Sales(Product(Name),Total)",
"value": [
{ "Product": { "Name": "Paper" },
"Total@type": "Decimal", "Total": 4 },
{ "Product": { "Name": "Sugar" },
"Total@type": "Decimal", "Total": 4 }
]
}
```
### {

```
"@context": "$metadata#Cities(Continent(Name),Country(Name),
TotalPopulation)",
"value": [
{ "Continent": { "Name": "Asia" }, "Country": { "Name": "China" },
"TotalPopulation@type": "Int32", "TotalPopulation": 1412000000 },
{ "Continent": { "Name": "Asia" }, "Country": { "Name": "India" },
"TotalPopulation@type": "Int32", "TotalPopulation": 1408000000 },
...
]
}
```

```
/concat(filter(CountryPopulation ge 10000000),
groupby((Continent/Name),
aggregate(CountryPopulation with sum
as TotalPopulation)))
```
_Example 98: assuming that_ **Amount** _is a custom aggregate in addition to the property, determine the total for countries with an_ **Amount** _greater
than 1000_

```
GET /service/SalesOrders?$apply=
groupby((Customer/Country),aggregate(Amount))
/filter(Amount gt 1000)
/aggregate(Amount)
```

# 8 Conformance

Conforming services MUST follow all rules of this specification for the set transformations and aggregation methods they

support. They MUST implement all set transformations and aggregation methods they advertise via the annotation
**ApplySupported**.

Conforming clients MUST be prepared to consume a model that uses any or all of the constructs defined in this

specification, including custom aggregation methods defined by the service, and MUST ignore any constructs not
defined in this version of the specification.


# Appendix A. References

This appendix contains the normative references that are used in this document.

While any hyperlinks included in this appendix were valid at the time of publication, OASIS cannot guarantee their long-
term validity.

## A.1 Normative References

The following documents are referenced in such a way that some or all of their content constitutes requirements of this

document.

**[OData-ABNF]**

_ABNF components: OData ABNF Construction Rules Version 4.01 and OData ABNF Test Cases._
See link in “Related work” section on cover page.

**[OData-Agg-ABNF]**

_OData Aggregation ABNF Construction Rules Version 4.0._
See link in “Additional artifacts” section on cover page.

**[OData-CSDL]**

_OData Common Schema Definition Language (CSDL) JSON Representation Version 4.01._
See link in “Related work” section on cover page.

_OData Common Schema Definition Language (CSDL) XML Representation Version 4.01._
See link in “Related work” section on cover page.

**[OData-JSON]**

_OData JSON Format Version 4.01._

See link in “Related work” section on cover page.

**[OData-Protocol]**

_OData Version 4.01. Part 1: Protocol._
See link in “Related work” section on cover page.

**[OData-URL]**

_OData Version 4.01. Part 2: URL Conventions._
See link in “Related work” section on cover page.

**[OData-VocAggr]**

_OData Aggregation Vocabulary._

See link in “Additional artifacts” section on cover page.

**[OData-VocCore]**

_OData Core Vocabulary._
See link in “Related work” section on cover page.

**[RFC21 19 ]**

_Bradner, S., “Key words for use in RFCs to Indicate Requirement Levels”, BCP 14, RFC 2119, DOI 10.17487/RFC2119,
March 1997_

https://www.rfc-editor.org/info/rfc21 19.


**[RFC8174]**

_Leiba, B., “Ambiguity of Uppercase vs Lowercase in RFC 2119 Key Words”, BCP 14, RFC 8174, DOI
10.17487/RFC8174, May 2017_

https://www.rfc-editor.org/info/rfc8174.


# Appendix B. Acknowledgments

## B.1 Special Thanks

The contributions of the OASIS OData Technical Committee members, enumerated in **[OData-Protocol, section C.2]** ,

are gratefully acknowledged.

## B.2 Participants

**OData TC Members:**

```
First Name Last Name Company
```
```
George Ericson Dell
```
```
Hubert Heijkers IBM
```
```
Ling Jin IBM
```
```
Stefan Hagen Individual
```
```
Michael Pizzo Microsoft
```
```
Christof Sprenger Microsoft
```
```
Ralf Handl SAP SE
```
```
Gerald Krause SAP SE
```
```
Heiko Theißen SAP SE
```
```
Martin Zurmuehl SAP SE
```

# Appendix C. Revision History

```
Revision Date Editor Changes Made
```
```
Working Draft 01 2012-
11-12
```
```
Ralf Handl Translated contribution into OASIS format
```
```
Committee
Specification Draft
01
```
```
2013-
07-25
```
```
Ralf Handl
Hubert
Heijkers
Gerald
Krause
Michael
Pizzo
Martin
Zurmuehl
```
```
Switched to pipe-and-filter-style query language based on
composable set transformations
Fleshed out examples and addressed numerous editorial and
technical issues processed through the TC
Added Conformance section
```
```
Committee
Specification Draft
02
```
```
2014-
01-09
```
```
Ralf Handl
Hubert
Heijkers
Gerald
Krause
Michael
Pizzo
Martin
Zurmuehl
```
```
Dynamic properties used all aggregated values either via aliases or
via custom aggregates
Refactored annotations
```
```
Committee
Specification Draft
03
```
```
2015-
07-16
```
```
Ralf Handl
Hubert
Heijkers
Gerald
Krause
Michael
Pizzo
Martin
Zurmuehl
```
```
Added compute transformation
Minor clean-up
```
```
Committee
Specification Draft
04
```
```
2023-
07-05
```
```
Ralf Handl
Hubert
Heijkers
Gerald
Krause
Michael
Pizzo
Heiko
Theißen
```
```
Added section about fundamentals of input and output sets
Algorithmic descriptions of transformations
Added join and outerjoin transformations, replaced expand by
addnested
Added transformations orderby, skip, top, nest
Added transformations for recursive hierarchies, updated related
filter functions
Added functions evaluable on a collection, introduced keyword
$these
Merged section 4 “Representation of Aggregated Instances” into
section 3
Remove actions and functions (except set transformations) on
aggregated entities, adapted section “Actions and Functions on
Aggregated Entities”
```

**Revision Date Editor Changes Made**

Committee
Specification 03

```
2023-
09-19
```
```
Ralf Handl
Gerald
Krause
Heiko
Theißen
```
```
Non-material changes from public review feedback
```
Committee
Specification Draft

05

```
2025-
10-01
```
```
Gerald
Krause
Heiko
Theißen
```
```
Remove sections not intended for OASIS Standard
```
Committee

Specification 04

```
2025-
11-18
```
```
Gerald
Krause
Heiko
Theißen
```
```
No changes from public review
```

# Appendix D. Notices

Copyright © OASIS Open 2025. All Rights Reserved.

All capitalized terms in the following text have the meanings assigned to them in the OASIS Intellectual Property Rights
Policy (the “OASIS IPR Policy”). The full Policy may be found at the OASIS website.

This document and translations of it may be copied and furnished to others, and derivative works that comment on or

otherwise explain it or assist in its implementation may be prepared, copied, published, and distributed, in whole or in
part, without restriction of any kind, provided that the above copyright notice and this section are included on all such

copies and derivative works. However, this document itself may not be modified in any way, including by removing the
copyright notice or references to OASIS, except as needed for the purpose of developing any document or deliverable
produced by an OASIS Technical Committee (in which case the rules applicable to copyrights, as set forth in the OASIS

IPR Policy, must be followed) or as required to translate it into languages other than English.

The limited permissions granted above are perpetual and will not be revoked by OASIS or its successors or assigns.

This document and the information contained herein is provided on an “AS IS” basis and OASIS DISCLAIMS ALL
WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO ANY WARRANTY THAT THE USE OF
THE INFORMATION HEREIN WILL NOT INFRINGE ANY OWNERSHIP RIGHTS OR ANY IMPLIED WARRANTIES OF
MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE.

As stated in the OASIS IPR Policy, the following three paragraphs in brackets apply to OASIS Standards Final
Deliverable documents (Committee Specification, Candidate OASIS Standard, OASIS Standard, or Approved Errata).

[OASIS requests that any OASIS Party or any other party that believes it has patent claims that would necessarily be
infringed by implementations of this OASIS Standards Final Deliverable, to notify OASIS TC Administrator and provide
an indication of its willingness to grant patent licenses to such patent claims in a manner consistent with the IPR Mode
of the OASIS Technical Committee that produced this deliverable.]

[OASIS invites any party to contact the OASIS TC Administrator if it is aware of a claim of ownership of any patent

claims that would necessarily be infringed by implementations of this OASIS Standards Final Deliverable by a patent
holder that is not willing to provide a license to such patent claims in a manner consistent with the IPR Mode of the
OASIS Technical Committee that produced this OASIS Standards Final Deliverable. OASIS may include such claims on

its website, but disclaims any obligation to do so.]

[OASIS takes no position regarding the validity or scope of any intellectual property or other rights that might be claimed

to pertain to the implementation or use of the technology described in this OASIS Standards Final Deliverable or the

extent to which any license under such rights might or might not be available; neither does it represent that it has made
any effort to identify any such rights. Information on OASIS’ procedures with respect to rights in any document or
deliverable produced by an OASIS Technical Committee can be found on the OASIS website. Copies of claims of rights
made available for publication and any assurances of licenses to be made available, or the result of an attempt made to

obtain a general license or permission for the use of such proprietary rights by implementers or users of this OASIS

Standards Final Deliverable, can be obtained from the OASIS TC Administrator. OASIS makes no representation that
any information or list of intellectual property rights will at any time be complete, or that any claims in such list are, in
fact, Essential Claims.]

The name “OASIS” is a trademark of OASIS, the owner and developer of this specification, and should be used only to
refer to the organization and its official outputs. OASIS welcomes reference to, and implementation and use of,

specifications, while reserving the right to enforce its marks against misleading uses. Please see https://www.oasis-
open.org/policies-guidelines/trademark/ for above guidance.


