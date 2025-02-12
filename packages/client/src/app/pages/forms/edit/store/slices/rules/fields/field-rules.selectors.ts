import type { RootState } from '@editor/store';
import type { FieldRule } from '@ff-client/types/rules';

export const fieldRuleSelectors = {
  one:
    (fieldUid: string) =>
    (state: RootState): FieldRule | undefined =>
      state.rules.fields.items.find((rule) => rule.field === fieldUid),
  isInCondition:
    (fieldUid: string) =>
    (state: RootState): boolean =>
      state.rules.fields.items.some((rule) =>
        rule.conditions.some((condition) => condition.field === fieldUid)
      ) ||
      state.rules.pages.items.some((rule) =>
        rule.conditions.some((condition) => condition.field === fieldUid)
      ),
  hasRule:
    (fieldUid: string) =>
    (state: RootState): boolean =>
      !!state.rules.fields.items.find((rule) => rule.field === fieldUid),
} as const;
