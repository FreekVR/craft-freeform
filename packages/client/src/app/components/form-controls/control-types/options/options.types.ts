import type {
  GenericValue,
  OptionCollection,
  Property,
} from '@ff-client/types/properties';

export enum Source {
  Custom = 'custom',
  Elements = 'elements',
  Predefined = 'predefined',
}

export const sourceLabels: OptionCollection = [
  {
    value: 'custom',
    label: 'Custom',
  },
  {
    value: 'elements',
    label: 'Elements',
  },
  {
    value: 'predefined',
    label: 'Predefined',
  },
];

export type Option = {
  label: string;
  value: string;
};

export type ElementOptionType = {
  typeClass: string;
  label: string;
  properties: Property[];
};

type BaseOptions = {
  source: Source;
};

export type ConfigurableOptionsConfiguration = BaseOptions & {
  source: Source.Elements | Source.Predefined;
  typeClass: string;
  properties: GenericValue;
};

export type CustomOptionsConfiguration = BaseOptions & {
  source: Source.Custom;
  useCustomValues: boolean;
  options: Option[];
};

export type OptionsConfiguration =
  | CustomOptionsConfiguration
  | ConfigurableOptionsConfiguration;

export type ConfigurationProps<
  T extends OptionsConfiguration = OptionsConfiguration,
> = {
  value: T;
  updateValue: (value: T) => void;
  defaultValue: string | string[];
  updateDefaultValue: (value: string | string[]) => void;
  convertToCustomValues?: () => void;
  isMultiple?: boolean;
};
